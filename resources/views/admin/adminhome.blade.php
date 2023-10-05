@extends('layouts.app_admin')

    @section('title', 'Admin Form Login')

    @section('content')
        <header class="header text-center p-3">
                <!-- <div class="mt-20 p-20">
                <h2><b>Dashboard</b></h2>
                </div> -->
        </header>
        <div class="container">
        <h3>Welcome to the Quiz Admin Dashboard</h3>

<p>Manage and oversee your quizzes with ease using our intuitive admin dashboard. Streamline your quiz creation, monitor participant progress, and analyze results—all in one central hub.</p>

<h4>Key Features:</h4>

<ol>
    <li>Create Quizzes: Effortlessly craft engaging quizzes with our user-friendly quiz builder. Customize questions, set time limits, and define scoring parameters.</li>
    <li>Monitor Participants: Keep track of participant activity in real-time. View live updates on quiz attempts, completion status, and scores.</li>
    <li>Analytics & Insights: Dive into detailed analytics to gain valuable insights. Identify trends, track performance, and understand participant behavior to enhance future quizzes.</li>
    <li>User Management: Administer user accounts with ease. Add, remove, or modify user roles to ensure a secure and organized quiz environment.</li>
    <li>Customization Options: Tailor the look and feel of your quizzes. Choose from a range of themes and branding options to create a seamless experience for participants.</li>
    <li>Notification Center: Stay informed with instant notifications. Receive alerts on quiz submissions, important updates, and system notifications.</li>
    <li>Data Security: Rest easy with robust data security measures. Your quizzes and participant data are handled with the utmost confidentiality and compliance.</li>
</ol>

<h4>Getting Started:</h4>

<ol>
    <li>Create a Quiz: Click on "Create Quiz" to start building your quiz from scratch or choose from our template library.</li>
    <li>Monitor Progress: Navigate to the "Dashboard" to view real-time statistics on quiz engagement and completion rates.</li>
    <li>Analytics: Explore the "Analytics" section to gain insights into participant performance and quiz effectiveness.</li>
    <li>User Management: Access "User Settings" to manage accounts and control access levels for a seamless user experience.</li>
</ol>

<p>Unlock the power of efficient quiz management. Explore, analyze, and optimize your quizzes effortlessly with our admin dashboard.</p>

        </div>
        <!-- <div class="text-start">
            <h5><b>Quizes List:</b></h5>
        </div> -->
            <!-- @if(!empty($data[0]))
            <div class="container">
                @foreach ($data[0] as $quiz)
                <div class="row m-1 p-3 border-bottom">
                    <div class="col-10 text-start">
                        # {{ $quiz->id }}: {{ $quiz->quiz_name }}
                    </div>    
                    <div class="col-1">
                        <a href="admin/editQuiz/{{ $quiz->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                    </div>
                    <div class="col-1">
                        <a href="/delete_quiz/{{ $quiz->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif -->
        <!-- <div class="text-start">
            <br>
            <h5><b>Users List:</b></h5>
        </div> -->
            <!-- @if(!empty($data[1]))
            <div class="container">
                @foreach ($data[1] as $user)
                <div class="row m-1 p-3 border-bottom">
                    <div class="col-10 text-start">
                    @if($user->is_admin == 1)
                        <i class="fa-sharp fa-solid fa-user-secret"></i>&nbsp;
                        @endif
                    {{ $user->username }} ({{ $user->email }})
                    </div>
                    <div class="col-1">
                        <a href="/admin/editUser/{{ $user->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                    </div>
                    <div class="col-1">
                        <a href="/admin/deleteUser/{{ $user->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif -->
        <div class="text-start">
            <!-- <h4>Sessions List:</h4> -->
        </div>
        <?php
            use \App\Http\Controllers\SessionController;  
        ?>
        
            @if(!empty($data[2]))
            <div class="container">
                
            </div>
            @endif

    @endsection
