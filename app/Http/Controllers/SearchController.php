<?php

namespace App\Http\Controllers;

use App\User;

class SearchController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  string  $name
     * @return Response
     */
    public function show($name)
    {
        return User::findOrFail($id);
    }
}