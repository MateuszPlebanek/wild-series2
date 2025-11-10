<?php
namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $total = 0;

        foreach ($program->getSeasons() as $season) {
            foreach ($season->getEpisodes() as $episode) {
                $total += (int) ($episode->getDuration() ?? 0);
            }
        }

        if ($total <= 0) {
            return '0 minute';
        }

        $days = intdiv($total, 1440);         // 24 * 60
        $rem  = $total % 1440;
        $hours = intdiv($rem, 60);
        $mins  = $rem % 60;

        $parts = [];
        if ($days > 0)  { $parts[] = $days.' jour'.($days>1?'s':''); }
        if ($hours > 0) { $parts[] = $hours.' heure'.($hours>1?'s':''); }
        if ($mins > 0)  { $parts[] = $mins.' minute'.($mins>1?'s':''); }

        return implode(' ', $parts);
    }
}
