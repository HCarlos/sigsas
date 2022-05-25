<div id="datatable-buttons_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="position: relative; z-index: 0">
    <div class="col-sm-12">
        <div class="row">
            <table  id="tblCat" class="table table-bordered table-striped dt-responsive dataTable nowrap  " role="grid" aria-describedby="datatable-buttons_info" style="width: 100%; position: relative; z-index:0;" width="100%">
                <thead>
                    <tr role="row">
                        <th class="sorting_asc" aria-sort="ascending" aria-label="Name: activate to sort column descending">ID</th>
                        <th class="sorting" >Comunidad</th>
                        <th class="sorting" >Delegado</th>
                        <th class="sorting" >Tipocomunidad</th>
                        <th class="sorting" >Nomenclatura</th>
                        <th style="width: 100vw"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="table-user">{{$item->id}}</td>
                        <td>{{ $item->comunidad }}</td>
                        <td>{{ $item->delegado->FullName }}</td>
                        <td>{{ $item->tipoComunidad->tipocomunidad }}</td>
                        <td>{{ $item->nomenclatura }}</td>
                        <td class="table-action w-100">
                            <div class="button-list">
                                @include('SIGSAS.xFiles.UI_Kit.__edit_item')
                                @include('SIGSAS.xFiles.UI_Kit.__remove_item')
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>