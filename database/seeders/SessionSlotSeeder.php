<?php

namespace Database\Seeders;

use App\Models\Session;
use App\Models\SessionSlot;
use App\Models\Slot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SessionSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sessions = Session::all();

        foreach ($sessions as $session) {
            $slots = Slot::where('hall_id', $session->hall_id)->get();

            foreach ($slots as $slot) {
                SessionSlot::create([
                    'session_id' => $session->id,
                    'slot_id' => $slot->id,
                    'status' => 'available',
                ]);
            }
        }
    }
}
