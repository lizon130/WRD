<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\RegisteredEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredEventController extends Controller
{
    /**
     * Display a listing of the registered events.
     */
    public function list()
    {
        $registeredEvents = RegisteredEvent::where('user_id', Auth::id())->get();

        return response()->json([
            'success' => true,
            'data' => $registeredEvents
        ], 200);
    }

    /**
     * Store a newly created registered event in the database.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|exists:events,id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:15',
                'gender' => 'required|in:Male,Female,Other',
                'present_address' => 'required|string',
                'permanent_address' => 'required|string',
            ]);

            // Check if the user exists by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                // Create a new user if they don't exist
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->phone), // Use phone as the password
                ]);
            }

            // Check if the user is already registered for the event
            $alreadyRegistered = RegisteredEvent::where('event_id', $request->event_id)
                ->where('user_id', $user->id)
                ->exists();

            if ($alreadyRegistered) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already registered for this event.',
                ], 409);
            }

            // Create a new registered event
            $registeredEvent = RegisteredEvent::create([
                'event_id' => $request->event_id,
                'user_id' => $user->id,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email ?? null,
                'phone' => $request->phone ?? null,
                'gender' => $request->gender ?? null,
                'present_address' => $request->present_address ?? null,
                'permanent_address' => $request->permanent_address ?? null,
                'grade' => $request->grade ?? null,
                'school' => $request->school ?? null,
                'studentId' => $request->studentId ?? null,
                'interests' => $request->interests ?? null,
                'participation' => $request->participation ?? null,
                'diet' => $request->diet ?? null,
                'notes' => $request->notes ?? null,
                'terms' => $request->terms ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful.',
                'data' => $registeredEvent,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the details of a specific registered event.
     */
    public function details($id)
    {
        $registeredEvent = RegisteredEvent::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$registeredEvent) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized access.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $registeredEvent
        ], 200);
    }

    /**
     * Update the specified registered event.
     */
    public function update(Request $request, $id)
    {
        $registeredEvent = RegisteredEvent::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$registeredEvent) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized access.'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:15',
            'gender' => 'sometimes|in:Male,Female,Other',
            'present_address' => 'sometimes|string',
            'permanent_address' => 'sometimes|string',
        ]);

        $registeredEvent->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $registeredEvent
        ], 200);
    }

    /**
     * Remove the specified registered event from the database.
     */
    public function delete($id)
    {
        $registeredEvent = RegisteredEvent::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$registeredEvent) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized access.'
            ], 404);
        }

        $registeredEvent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event registration deleted successfully.'
        ], 200);
    }
}
