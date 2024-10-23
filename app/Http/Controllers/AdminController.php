<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function Show_users_dashboard()
    {
        $response = $this->userService->get_Users_For_admin();
        $users = $response['users'];

        return view('admin.User.users', compact('users'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);

        $dashboardData = DB::select("SELECT sum(total) as TotalAmount,
                                    sum(if(status='orderd',total,0)) as TotalOrderdAmount,
                                    sum(if(status='delivered',total,0)) as TotalDeliveredAmount,
                                    sum(if(status='canceled',total,0))as TotalCanceledAmount,
                                    count(*) as Total,
                                    sum(if(status='orderd',1,0)) as TotalOrderd ,
                                    sum(if(status='delivered',1,0)) as TotalDelivered ,
                                    sum(if(status='canceled',1,0))as TotalCanceled
                                    FROM `orders`
                                    ");
        $dashboardData = $dashboardData[0];

        $monthlydata = DB::SELECT("SELECT M.id  As MonthNo , M.name As MonthName,
                                                    IFNULL(D.TotalAmount,0) As TotalAmount,
                                                    IFNULL(D.TotalOrderdAmount,0)      As TotalOrderdAmount,
                                                    IFNULL(D.TotalDeliveredAmount,0)   As TotalDeliveredAmount,
                                                    IFNULL(D.TotalCanceledAmount,0)    As TotalCanceledAmount
                                From month_names As M
                                LEFT JOIN (
                                            SELECT
                                                    date_format(created_at,'%b') As MonthName,
                                                    Month(created_at) As MonthNo,
                                                    SUM(total) As TotalAmount,
                                                    SUM(if(status='orderd',total,0))    As TotalOrderdAmount,
                                                    SUM(if(status='delivered',total,0)) As TotalDeliveredAmount,
                                                    SUM(if(status='canceled',total,0))  As TotalCanceledAmount
                                                    FROM   orders
                                                    WHERE Year(created_at)= year(now())
                                                    GROUP By Year(created_at),Month(created_at),date_format(created_at,'%b')
                                                    ORDER By Month(created_at)
                                            ) As D
                                                    On D.MonthNo = M.id
                                ");
        $AmountM = implode(',', collect($monthlydata)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlydata)->pluck('TotalOrderdAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlydata)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlydata)->pluck('TotalCanceledAmount')->toArray());

        $TotalAmount = Collect($monthlydata)->sum('TotalAmount');
        $TotalOrderdAmount = Collect($monthlydata)->sum('TotalOrderdAmount');
        $TotalDeliveredAmount = Collect($monthlydata)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = Collect($monthlydata)->sum('TotalCanceledAmount');



        return view('admin.index', compact(
            'orders',
            'dashboardData',
            'AmountM',
            'OrderedAmountM',
            'DeliveredAmountM',
            'CanceledAmountM',
            'TotalAmount',
            'TotalOrderdAmount',
            'TotalDeliveredAmount',
            'TotalCanceledAmount'
        ));
    }

    public function getUserData() {
        $user=User::find(Auth::user()->id);
        return view('admin.User.setting', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
