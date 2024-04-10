<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">Users
                            <p><i class="fas fa-user fa-fw" style="font-size:100px;"></i></p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#" style="font-size:18px;"> 
                                {{App\Models\User::all()->count()}}
                            </a>
                            <div class="small text-white"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">Faculty
                            <p><i class="fas fa-home" style="font-size:100px;"></i></p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#" style="font-size:18px;">
                                {{App\Models\Faculty::all()->count()}}
                            </a>
                            <div class="small text-white"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">Event
                            <p><i class="fas fa-envelope" style="font-size:100px;"></i></p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#" style="font-size:18px;">
                                {{App\Models\Event::all()->count()}}
                            </a>
                            <div class="small text-white"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body"> Contribution
                            <p><i class="fas fa-book" style="font-size:100px;"></i></p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#" style="font-size:18px;">
                                
                                {{App\Models\Contribution::all()->count()}}
                            </a>
                            <div class="small text-white"></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">Your Details</div>
                         <div class="card-header" style="background-color: orange">Email:{{Auth::user()->email}}</div>
                         <div class="card-header" style="background-color: orange">Address:{{Auth::user()->address}}</div>
                         <div class="card-header" style="background-color: orange">Mobile Phone number:{{Auth::user()->phone_number}}</div>
                         <div class="card-header" style="background-color: orange">Designation:{{Auth::user()->designation}}</div>
                         <div class="card-header" style="background-color: orange">Start date:{{Auth::user()->start_from}}</div>
                         <div class="card-header" style="background-color: orange">Faculty:{{Auth::user()->faculty->name}}</div>
                         <div class="card-header " style="background-color: orange"><p class="badge rounded-pill bg-success">Role:{{Auth::user()->role->name}}</p></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-area me-1"></i>
                            Area Chart Example
                        </div>
                        <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                            Bar Chart Example
                        </div>
                        <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                    </div>
                </div>
            </div>
            {{-- <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    DataTable Example
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                       
                    </table>
                </div>
            </div> --}}
        </div>
    </main>
