<!DOCTYPE html>
<html lang="en">
<head>
    @include('base.head')
</head>
<body>
    <table class="table table-hover" id="prev-item-table">
        <thead>
          <tr>
              <th>No</th>
              <th>Outlet</th>
              <th>Total</th>
              <th>Paid</th>
              <th>Due</th>
          </tr>
        </thead>
        <tbody id="prev-item-body" class="fw-normal">
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6"></td>
            <td class="text-start">
              <span class="fw-bold">Total</span>
            </td>
            <td class="">
              <span class="fw-bold" id="prev-total"></span>
            </td>
          </tr>
        </tfoot>
    </table>


    @include('base.script')
</body>
</html>
