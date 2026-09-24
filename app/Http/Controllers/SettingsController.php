<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Support\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('settings.index', ['settings' => Settings::all()]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        Settings::setMany($request->validated());

        return back()->with('success', 'Settings updated successfully.');
    }
}
