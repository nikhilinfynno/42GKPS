<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
      
        $authUser = Auth::user();
        $totalUsers = User::query()->activeStatus()->count();
        $totalFamilies = User::query()->activeStatus()->hof()->count();
        $dayType = CommonHelper::getDayTypeOfCurrentUser();
        return view('dashboard.index', compact('totalUsers', 'totalFamilies', 'dayType'));
    }
}
