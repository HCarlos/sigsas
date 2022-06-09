<div class="row bg-dark-lighten rounded ">
    @if( $items->total() > 15 )
        <div class="col-md-6 mb-0" >
            @include('SIGSAS.xFiles.UI_Kit.__toolbar_denuncia')
        </div>
        <div class="col-md-6 ">
            <div class="mt-md-2 float-right mr-5">
                {{ $items->onEachSide(1)->links() }}
            </div>
        </div>
    @else
        <div class="col-md-12 mb-0" >
            @include('SIGSAS.xFiles.UI_Kit.__toolbar_denuncia')
        </div>
    @endif
</div>
