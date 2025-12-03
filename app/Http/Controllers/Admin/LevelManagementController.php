<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrozenLevel;
use App\Models\User;
use Illuminate\Http\Request;

class LevelManagementController extends Controller
{
    public function index()
    {
        $levels = User::levels();
        $frozen = FrozenLevel::pluck('is_frozen', 'level')->toArray();

        return view('admin.freeze_levels.index', compact('levels', 'frozen'));
    }

    public function update(Request $request)
    {
        $levels = $request->levels ?? [];

        FrozenLevel::query()->update(['is_frozen' => false]);
        foreach ($levels as $lv) {
            FrozenLevel::updateOrCreate(
                ['level' => $lv],
                ['is_frozen' => true]
            );
        }

        return back()->with('success', 'Selected levels frozen successfully.');
    }
}