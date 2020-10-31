<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | General Form Elements</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('/public/assets/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('/public/assets/css/adminlte.min.css')}}">
  <meta name="csrf-token" content= "{{ csrf_token() }}" >

  <style>
    .error{
      color:red;
      font-weight: normal !important;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  @include('includes.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Ticket</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-8">
          @if(session()->has('msg'))
          <div class="alert alert-success" role="alert">
            {!! session()->get('msg') !!}
          </div>
          @endif
          @if(session()->has('error'))
          <div class="alert alert-danger" role="alert">
            {!! session()->get('error') !!}
          </div>
          @endif
            <!-- general form elements -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action="{{asset('/saveTicket')}}" id="ticketForm">
                {{ csrf_field() }}
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" readonly class="form-control" id="name" name="name" value="{{ session('user')['userName'] }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="text" readonly class="form-control" id="email" name="email" value="{{ session('user')['userEmail'] }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Department</label>
                    <select class="form-control" id="department" name="department" >
                      <option value=""> -- Select Department --</option>
                      @if(!empty($depts))
                        @foreach($depts as $dept)
                          
                          <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Category</label>
                    <select class="form-control" id="category" name="category" >
                      <option value=""> -- Select Category --</option>
                      @if(!empty($cats))
                        @foreach($cats as $cat)
                          <option value="{{ $cat->catName }}">{{ $cat->catName }}</option>
                        @endforeach
                      @endif
                    </select>
                    
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword2">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Priority</label>
                    <select class="form-control" id="priority" name="priority" >
                      <option value=""> -- Select Priority --</option>
                      <option value="High">High</option>
                      <option value="Medium">Medium</option>
                      <option value="Low">Low</option>
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.1.0-pre
    </div>
    <strong>Copyright &copy; 2014-2020 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('/public/assets/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('/public/assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('/public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- bs-custom-file-input -->
<script src="{{asset('/public/assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('/public/assets/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('/public/assets/js/demo.js')}}"></script>
<script>
$(document).ready(function(){
  $("#ticketForm").validate({
    rules:{
      'name':{
        required:true
      },
      'email':{
        required: true
      },
      'department':{
        required: true
      },
      'category':{
        required: true
      },
      'subject':{
        required: true
      },
      'description':{
        required: true
      },
      'priority':{
        required: true
      }
    },
    messages:{
      'name':{
        required:"Please enter name"
      },
      'email':{
        required: "Please enter email"
      },
      'department':{
        required: "Please select Department"
      },
      'category':{
        required: "Please enter Category"
      },
      'subject':{
        required: "Please enter subject"
      },
      'description':{
        required: "Please enter description"
      },
      'priority':{
        required: "Please select priority"
      }
    },
    submitHandler:function(){
      $("#ticketForm")[0].submit();
    }
  });
});
</script>
</body>
