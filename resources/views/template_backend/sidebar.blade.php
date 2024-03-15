<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link" style="">
        <span class="brand-text font-weight-light">Dompet Digital</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @if (Auth::user()->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link" id="home">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview" id="liMasterData">
                        <a href="#" class="nav-link" id="MasterData">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>
                                Administrator
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a href="{{ route('students.classrooms') }}" class="nav-link" id="DataStudents">
                                    <i class="fas fa-user-graduate nav-icon"></i>
                                    <p>Siswa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('parents.index') }}" class="nav-link" id="DataParents">
                                    <i class="fas fa-user nav-icon"></i>
                                    <p>Wali Murid</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('teachers.index') }}" class="nav-link" id="DataTeachers">
                                    <i class="fas fa-users nav-icon"></i>
                                    <p>Wali Kelas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('classrooms.index') }}" class="nav-link" id="DataClassrooms">
                                    <i class="fas fa-chalkboard-teacher nav-icon"></i>
                                    <p>Kelas Siswa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admins.index') }}" class="nav-link" id="DataAdmins">
                                    <i class="fas fa-users nav-icon"></i>
                                    <p>Admin</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('transactions.entry-balance') }}" class="nav-link" id="EntryBalance">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p>Input Saldo Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('transactions.classrooms') }}" class="nav-link" id="TransactionHistory">
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>Histori Belanja Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('settings') }}" class="nav-link" id="Settings">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Pengaturan</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
