<style type="text/css">
    @media only screen and (max-width: 600px) {
      .content {
          height:  400px;
          overflow: scroll;
      }
      #content_kawasan {
        height: 400px;
        overflow: scroll;
      }

      #example1 {
        font-size: 14px;
      }

      h1 {
      font-size: 20px !important;
      }
    }

    .approve{
      background-color: green;
      color:white;
      font-weight: bolder;
    }

    .waiting{
      background-color: yellow;
      color:black;
      font-weight: bolder;
    }

    .reject{
      background-color: red;
      color:white;
      font-weight: bolder;
    }

    .header_1 {
      background-color:#32c5d2 !important;
      color:white;
    }

    .modal-open {
  overflow: hidden;
}

.modal {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1050;
  display: none;
  overflow: hidden;
  outline: 0;
}

.modal-open .modal {
  overflow-x: hidden;
  overflow-y: auto;
}

.modal-dialog {
  position: relative;
  width: auto;
  margin: 0.5rem;
  pointer-events: none;
}

.modal.fade .modal-dialog {
  transition: transform 0.3s ease-out;
  transform: translate(0, -25%);
}

@media screen and (prefers-reduced-motion: reduce) {
  .modal.fade .modal-dialog {
    transition: none;
  }
}

.modal.show .modal-dialog {
  transform: translate(0, 0);
}

.modal-dialog-centered {
  display: flex;
  align-items: center;
  min-height: calc(100% - (0.5rem * 2));
}

.modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 100%;
  pointer-events: auto;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 0.3rem;
  box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.5);
  outline: 0;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1040;
  background-color: #000;
}

.modal-backdrop.fade {
  opacity: 0;
}

.modal-backdrop.show {
  opacity: 0.5;
}

.modal-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
  border-top-left-radius: 0.3rem;
  border-top-right-radius: 0.3rem;
}

.modal-header .close, .modal-header .mailbox-attachment-close {
  padding: 1rem;
  margin: -1rem -1rem -1rem auto;
}

.modal-title {
  margin-bottom: 0;
  line-height: 1.5;
}

.modal-body {
  position: relative;
  flex: 1 1 auto;
  padding: 1rem;
}

.modal-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 1rem;
  border-top: 1px solid #e9ecef;
}

.modal-footer > :not(:first-child) {
  margin-left: .25rem;
}

.modal-footer > :not(:last-child) {
  margin-right: .25rem;
}

.modal-scrollbar-measure {
  position: absolute;
  top: -9999px;
  width: 50px;
  height: 50px;
  overflow: scroll;
}

@media (min-width: 576px) {
  .modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
  }
  .modal-dialog-centered {
    min-height: calc(100% - (1.75rem * 2));
  }
  .modal-content {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);
  }
  .modal-sm {
    max-width: 300px;
  }
}

@media (min-width: 992px) {
  .modal-lg {
    max-width: 800px;
  }
}
.show {
  opacity: 1;
}
  </style>

<script src="https://unpkg.com/ionicons@4.2.4/dist/ionicons.js"></script>
<!-- jQuery -->
<script src="{{ url('/') }}/assets/users/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('/') }}/assets/users/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="{{ url('/') }}/assets/users/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ url('/') }}/assets/users/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- SlimScroll -->
<script src="{{ url('/') }}/assets/users/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="{{ url('/') }}/assets/users/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{ url('/') }}/assets/users/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('/') }}/assets/users/dist/js/demo.js"></script>

<!-- ChartJS 1.0.1 -->
<script src="{{ url('/') }}/assets/users/plugins/chartjs2/Chart.min.js"></script>

<script type="text/javascript">
  function setapproved(values){
    var budget_id = $("input[name^='budget_id']").serializeArray();
    if ( values == "6" ){
      $("#title_approval").attr("style","color:blue");
      $("#title_approval").text("These workorder will be APPROVED by You");
    }else{
      $("#title_approval").attr("style","color:red");
      $("#title_approval").text("These workorder will be REJECTED by You");
    }
    $("#btn_save_budgets").attr("data-value",values);
   
  }

  
</script>