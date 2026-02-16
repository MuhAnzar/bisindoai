<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModeCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ModeCardAdminController extends Controller
{
    /**
     * Display a listing of mode cards.
     */
    public function index(): View
    {
        $modeCards = ModeCard::ordered()->get();
        
        return view('admin.mode_cards.index', compact('modeCards'));
    }

    /**
     * Show the form for editing a mode card.
     */
    public function edit(ModeCard $modeCard): View
    {
        return view('admin.mode_cards.edit', compact('modeCard'));
    }

    /**
     * Update the specified mode card.
     */
    public function update(Request $request, ModeCard $modeCard): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'badge_text' => 'required|string|max:50',
            'badge_emoji' => 'nullable|string|max:10',
            'gradient_from' => 'required|string|max:7',
            'gradient_to' => 'required|string|max:7',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string|max:255',
            'button_text' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($modeCard->image) {
                Storage::disk('public')->delete($modeCard->image);
            }
            
            $imagePath = $request->file('image')->store('mode_cards', 'public');
            $validated['image'] = $imagePath;
        }

        // Filter empty features
        $validated['features'] = array_values(array_filter($validated['features']));

        $modeCard->update($validated);

        return redirect()
            ->route('admin.mode-cards.index')
            ->with('success', 'Mode card "' . $modeCard->title . '" berhasil diperbarui!');
    }

    /**
     * Remove the image from a mode card.
     */
    public function removeImage(ModeCard $modeCard): RedirectResponse
    {
        if ($modeCard->image) {
            Storage::disk('public')->delete($modeCard->image);
            $modeCard->update(['image' => null]);
        }

        return redirect()
            ->route('admin.mode-cards.edit', $modeCard)
            ->with('success', 'Gambar berhasil dihapus!');
    }
}
