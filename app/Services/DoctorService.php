<?php

namespace App\Services;

use App\DataTransferObjects\DoctorsNetworkDTO;
use App\Models\Doctor;
use App\Models\DoctorNetwork;
use App\Models\Specialization;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    public function findNetworkById(int $id, DoctorsNetworkDTO $doctorsNetworkDTO): array
    {
        $specializationName = $doctorsNetworkDTO->getSpecializationName();
        $minYoe = $doctorsNetworkDTO->getMinYoe();
        $maxYoe = $doctorsNetworkDTO->getMaxYoe();

        $specialization = Specialization::where('specialization', $specializationName)->firstOrFail();
        $specializationId = $specialization->id;

        $reachableDoctorIds = $this->getReachableDoctorIdsWithSpecialization($id, $specializationId);

        if (empty($reachableDoctorIds)) {
            $response = ['specializations_aggregrates' => []];
            if ($minYoe !== null || $maxYoe !== null) {
                $response['years_of_experience_aggregates'] = [];
            }
            return $response;
        }

        $specializationsAggregates = DB::table('specializations')
            ->join('doctors_specializations', 'specializations.id', '=', 'doctors_specializations.specialization_id')
            ->join('doctors', 'doctors.id', '=', 'doctors_specializations.doctor_id')
            ->whereIn('doctors.id', $reachableDoctorIds)
            ->when($minYoe !== null, function ($query) use ($minYoe) {
                $query->where('doctors.years_of_experience', '>=', $minYoe);
            })
            ->when($maxYoe !== null, function ($query) use ($maxYoe) {
                $query->where('doctors.years_of_experience', '<=', $maxYoe);
            })
            ->groupBy('specializations.specialization')
            ->select('specializations.specialization', DB::raw('COUNT(DISTINCT doctors.id) as count'))
            ->pluck('count', 'specializations.specialization');

        $response = ['specializations_aggregrates' => $specializationsAggregates];

        if ($minYoe !== null || $maxYoe !== null) {
            $yoeAggregates = DB::table('doctors')
                ->whereIn('doctors.id', $reachableDoctorIds)
                ->when($minYoe !== null, function ($query) use ($minYoe) {
                    $query->where('doctors.years_of_experience', '>=', $minYoe);
                })
                ->when($maxYoe !== null, function ($query) use ($maxYoe) {
                    $query->where('doctors.years_of_experience', '<=', $maxYoe);
                })
                ->groupBy('doctors.years_of_experience')
                ->select('doctors.years_of_experience', DB::raw('COUNT(*) as count'))
                ->pluck('count', 'doctors.years_of_experience');

            $response['years_of_experience_aggregates'] = $yoeAggregates;
        }

        return $response;
    }

    private function getReachableDoctorIdsWithSpecialization(int $startingDoctorId, int $specializationId): array
    {
        $doctorIdsWithSpecialization = Doctor::whereHas('specializations', static function ($query) use ($specializationId) {
            $query->where('specialization_id', $specializationId);
        })->pluck('id')->toArray();

        $doctorIdsWithSpecializationSet = array_flip($doctorIdsWithSpecialization);

        if (!isset($doctorIdsWithSpecializationSet[$startingDoctorId])) {
            return [];
        }

        $edges = DoctorNetwork::whereIn('doctor_1_id', $doctorIdsWithSpecialization)
            ->whereIn('doctor_2_id', $doctorIdsWithSpecialization)
            ->get(['doctor_1_id', 'doctor_2_id'])
            ->toArray();

        $adjacencyList = [];
        foreach ($edges as $edge) {
            $adjacencyList[$edge['doctor_1_id']][] = $edge['doctor_2_id'];
            $adjacencyList[$edge['doctor_2_id']][] = $edge['doctor_1_id']; // Include reverse connection
        }

        $visited = [];
        $queue = [];

        $visited[$startingDoctorId] = true;
        $queue[] = $startingDoctorId;

        while (!empty($queue)) {
            $currentDoctorId = array_shift($queue);

            if (isset($adjacencyList[$currentDoctorId])) {
                foreach ($adjacencyList[$currentDoctorId] as $neighborId) {
                    if (!isset($visited[$neighborId])) {
                        $visited[$neighborId] = true;
                        $queue[] = $neighborId;
                    }
                }
            }
        }

        return array_keys($visited);
    }
}
