<?php


namespace Asivas\Analytics\Facades;


use App\Analytics\AssignedReportsByUser;
use App\Analytics\AssignedReportsCounter;
use App\Analytics\AssignedWithAudioReportsCounter;
use App\Analytics\AverageTimeToReleasedReportsByModality;
use App\Analytics\CreatedNowReportCounter;
use App\Analytics\CreatedReports;
use App\Analytics\CreatedReportsByGroup;
use App\Analytics\CreatedReportsCounter;
use App\Analytics\DoctorsSignedReports;
use App\Analytics\FinishedReports;
use App\Analytics\FinishedReportsByGroup;
use App\Analytics\FinishedReportsByUser;
use App\Analytics\FinishedReportsCounter;
use App\Analytics\LockedReportsCounter;
use App\Analytics\LockedWithAudioReportsCounter;
use App\Analytics\PendingByModality;
use App\Analytics\PerformanceByUser;
use App\Analytics\RejectedReportsByDoctor;
use App\Analytics\RejectedReportsByDoctorByDate;
use App\Analytics\RejectedReportsByReason;
use App\Analytics\ReleasedByModality;
use App\Analytics\ReleasedReports;
use App\Analytics\ReleasedReportsByDate;
use App\Analytics\ReleasedReportsByGroup;
use App\Analytics\ReleasedReportsByUser;
use App\Analytics\ReleasedReportsCounter;
use App\Analytics\ReportsByGroup;
use App\Analytics\ReportsPending;
use App\Analytics\ReportsReleased;
use App\Analytics\SignedReports;
use App\Analytics\SignedReportsByGroup;
use App\Analytics\SignedReportsCounter;
use App\Analytics\TopDoctorsSignedReports;
use App\Analytics\TopReleasedReportsByModality;
use App\Analytics\TopTypistReports;
use App\Analytics\TopUserReleaseReports;
use App\Analytics\UnassignedReportsCounter;
use App\Analytics\UserPerformance;
use Illuminate\Support\Facades\Facade;

class Analytics extends Facade
{
    public static function releasedReportsByDate($from, $to)
    {
        return ReleasedReportsByDate::getData($from,$to);
    }

    public static function doctorsSignedReports($from,$to)
    {
        return DoctorsSignedReports::getData($from,$to);
    }

    public static function reportsPendingAndReleased($from, $to)
    {
        $released = ReportsReleased::getData($from,$to);
        $pending = ReportsPending::getData($from,$to);
        return $pending->concat($released);
    }

    public static function releasedByModality($from,$to)
    {
        return ReleasedByModality::getData($from,$to);
    }

    public static function pendingByModality($from, $to)
    {
        return PendingByModality::getData($from,$to);
    }

    public static function releasedReports($from, $to)
    {
        return ReleasedReports::getData($from,$to);
    }

    public static function signedReports($from, $to)
    {
        return SignedReports::getData($from,$to);
    }

    public static function finishedReports($from, $to)
    {
        return FinishedReports::getData($from,$to);
    }

    public static function createdReports($from, $to)
    {
        return CreatedReports::getData($from,$to);
    }

    public static function createdReportsByGroup($from, $to)
    {
        return CreatedReportsByGroup::getData($from,$to);
    }

    public static function finishedReportsByGroup($from, $to)
    {
        return FinishedReportsByGroup::getData($from,$to);
    }

    public static function signedReportsByGroup($from, $to)
    {
        return SignedReportsByGroup::getData($from,$to);
    }

    public static function releasedReportsByGroup($from, $to)
    {
        return ReleasedReportsByGroup::getData($from,$to);
    }

    public static function reportsByStatusByGroup($from, $to)
    {
        return Analytics::createdReportsByGroup($from, $to)
            ->concat(Analytics::finishedReportsByGroup($from, $to))
            ->concat(Analytics::signedReportsByGroup($from, $to))
            ->concat(Analytics::releasedReportsByGroup($from, $to));
    }

    public static function reportsByStatus($from, $to)
    {
        return Analytics::createdReports($from, $to)
            ->concat(Analytics::finishedReports($from, $to))
            ->concat(Analytics::signedReports($from, $to))
            ->concat(Analytics::releasedReports($from, $to));
    }

    public static function createdReportsCounter($from,$to)
    {
        return CreatedReportsCounter::getData($from,$to);
    }

    public static function createdNowReportsCounter($from,$to)
    {
        return CreatedNowReportCounter::getData($from,$to);
    }

    public static function unassignedReportsCounter($from,$to)
    {
        return UnassignedReportsCounter::getData($from,$to);
    }

    public static function assignedReportsCounter($from,$to)
    {
        return AssignedReportsCounter::getData($from,$to);
    }

    public static function lockedReportsCounter ($from,$to)
    {
        return LockedReportsCounter::getData($from,$to);
    }

    public static function assignedWithAudioReportsCounter($from,$to)
    {
        return AssignedWithAudioReportsCounter::getData($from,$to);
    }

    public static function lockedWithAudioReportsCounter ($from,$to)
    {
        return LockedWithAudioReportsCounter::getData($from,$to);
    }
    public static function finishedReportsCounter($from,$to)
    {
        return FinishedReportsCounter::getData($from,$to);
    }
    public static function signedReportsCounter($from,$to)
    {
        return SignedReportsCounter::getData($from,$to);
    }
    public static function releasedReportsCounter($from,$to)
    {
        return ReleasedReportsCounter::getData($from,$to);
    }

    public static function rejectedReportsByDoctor($from,$to)
    {
        return RejectedReportsByDoctor::getData($from,$to);
    }

    public static function rejectedReportsByReason($from,$to)
    {
        return RejectedReportsByReason::getData($from,$to);
    }

    public static function rejectionReportsByDoctorByDate($from,$to)
    {
        return RejectedReportsByDoctorByDate::getData($from,$to);
    }

    public static function topDoctorsSignedReports($from,$to)
    {
        return TopDoctorsSignedReports::query($from,$to)
            ->orderBy('signedReports','desc')
            ->limit(5)
            ->get();
    }

    public static function topUserReleaseReports($from,$to)
    {
        return TopUserReleaseReports::query($from,$to)
            ->orderBy('releasedReports','desc')
            ->limit(5)
            ->get();
    }

    public static function topReleasedReportsByModality($from,$to)
    {
        return TopReleasedReportsByModality::query($from,$to)
            ->orderBy('releasedReports','desc')
            ->limit(5)
            ->get();
    }

    public static function averageTimeToReleasedReportsByModality($from,$to)
    {
        return AverageTimeToReleasedReportsByModality::getData($from,$to);
    }

    public static function topTypistReports($from,$to)
    {
        return TopTypistReports::query($from,$to)
            ->orderBy('typedReports','desc')
            ->groupBy('typist')
            ->limit(5)
            ->get();
    }

    public static function reportsByGroup($from,$to,$params = null)
    {
        return ReportsByGroup::getData($from,$to,$params);
    }

    public static function userPerformance($from,$to,$params = null)
    {
        return UserPerformance::getData($from,$to,$params);
    }

    public static function performanceByUser($from,$to,$params = null)
    {
        return PerformanceByUser::getData($from,$to,$params);
    }

    public static function assignedReportsByUser($from,$to,$params = null)
    {
        return AssignedReportsByUser::getData($from,$to,$params);
    }

    public static function finishedReportsByUser($from,$to,$params = null)
    {
        return FinishedReportsByUser::getData($from,$to,$params);
    }

    public static function releasedReportsByUser($from,$to,$params = null)
    {
        return ReleasedReportsByUser::getData($from,$to,$params);
    }
}
