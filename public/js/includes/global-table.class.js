var GlobalTable = {

    INIT: function(){
        this.EVENTS();
    },

    EVENTS: function(){
        var targetTbl = $('table.global-table');
        var loader = targetTbl.attr('data-loader');
        var url = targetTbl.attr('data-url');
        targetTbl.DataTable({
            responsive: true,
            serverSide: true,
            bPaginate: true,
            searching: true,
            autoWidth : false,
            order: [[ 0, "desc" ]],
            processing: true,
            language: {
                processing: '<img src="'+loader+'" style="width:10%; margin-bottom:10px;">'
            },
            ajax: {
                url: url,
            },
            createdRow : function(row){
                var thisRow = $(row);
                thisRow.addClass('cntr');
            }
        });
    },

    AUDIT: function(){
        $(document).ready(function(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            let params = new URLSearchParams(window.location.search);
            let dateFrom = '';
            let dateTo = '';
            if(params.get("date") == undefined){
                dateFrom = today
                dateTo = today
            }else{
                dateFrom = params.get("date").split(' - ')[0];
                dateTo = params.get("date").split(' - ')[1];
            }
            $('input[name="dates"]').daterangepicker({
                opens : 'left',
                applyButtonClasses : 'btn--teal',
                cancelButtonClasses : 'btn-danger',
                autoApply: true,
                locale: { format: 'DD/MM/Y' },
                startDate: dateFrom, 
                endDate: dateTo
            });
    
            var dateSerialize = 'date=' + $("input[name='dates']").val();
            history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + dateSerialize);
    
            var targetTbl = $('table.global-table-audit');
            var loader = targetTbl.attr('data-loader');
            var url = targetTbl.attr('data-url');
            targetTbl.DataTable({
                responsive: true,
                serverSide: true,
                bPaginate: true,
                searching: true,
                autoWidth : false,
                order: [[ 0, "desc" ]],
                processing: true,
                language: {
                    processing: '<img src="'+loader+'" style="width:10%; margin-bottom:10px;">'
                },
                ajax: {
                    url: url,
                    data:{
                        datePicker: $("input[name='dates']").val(),
                    }
                },
                createdRow : function(row){
                    var thisRow = $(row);
                    thisRow.addClass('cntr');
                }
            });
        });
        $('.runSearch').on('click',function(event){
            var dateSerialize = 'date=' + $("input[name='dates']").val();
            history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + dateSerialize);
            location.reload();
        });
        $('.download-reports').on('click',function(){
            var data_open = $(this).attr('data-open-dl');
            window.open(data_open+window.location.search);
        });
    }
};