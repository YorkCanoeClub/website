<script>
    
    var tableLayout = "<'top'i><'clear'>rt<'bottom'p><'clear'>";
    var tableLayoutTab = "<'clear'>rt<'bottom'p><'clear'>";
    var tableLayoutBasic = "<'clear'>rt<'clear'>";

    var dateFormat = "{{DATEFORMAT}}";
    var dateSep = "{{DATESEP}}";

    function fnCreateSelect( aData ) {

        var r='<select><option value=""></option>', i, iLen=aData.length;
        for ( i=0 ; i<iLen ; i++ ) {
                r += '<option value="'+aData[i]+'">'+aData[i]+'</option>';
        }
        return r+'</select>';

    }

    function fnSplitSearchTerm (input) {

        // If input has a value split it
        // SearchTerm:::VisibleValue:::FilterTerm

        if (input != null && input != "") {

                var splitkeys = input.split(":::");

                if (splitkeys.length == 3) {
                        return splitkeys[1];
                } else if (splitkeys.length == 2) {
                        return splitkeys[1];
                } else if (splitkeys.length == 1) {
                        return splitkeys[0];
                }

        }

        return "";

    }

    function fnSplitFilterTerm (input) {

        // If input has a value split it
        // SearchTerm:::VisibleValue:::FilterTerm

        if (input != null && input != "") {

                var splitkeys = input.split(":::");

                if (splitkeys.length == 3) {
                        return splitkeys[2];
                } else if (splitkeys.length == 2) {
                        return splitkeys[1];
                } else if (splitkeys.length == 1) {
                        return splitkeys[0];
                }

        }

        return "";

    }

    $.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {

        var aiRows;
        if (typeof iColumn == "undefined") { return new Array(); }
        if (typeof bUnique == "undefined") { bUnique = true; }
        if (typeof bFiltered == "undefined") { bFiltered = true; }
        if (typeof bIgnoreEmpty == "undefined") { bIgnoreEmpty = true; }
        if (bFiltered == true) { aiRows = oSettings.aiDisplay; } else { aiRows = oSettings.aiDisplayMaster;	}

        var asResultData = new Array();

        for (var i=0,c=aiRows.length; i<c; i++) {

            iRow = aiRows[i];
            var aData = this.fnGetData(iRow);
            var sValue = aData[iColumn];

            if (bIgnoreEmpty == true && sValue.length == 0) { 
                    continue; 
            } else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) { 
                    continue; 
            } else {
                    asResultData.push(sValue);
            }

        }

        return asResultData.sort();

    };

    function defineTableNew(tableSelector, defaultSort, noSort, splitSort, storageLocation, zero, layout, widths, pagesize, drawCallBack) {

        if ($(tableSelector).length == 1) {

            if (widths == null || widths == "") {
                widths = true;
            }


            if (pagesize == null || pagesize == "") {
                pagesize = 15;
            }

            if (false) {
                bConsole(tableSelector);
                bConsole(defaultSort);
                bConsole(noSort);
                bConsole(splitSort);
                bConsole(storageLocation);
                bConsole(zero);
                bConsole(layout);
                bConsole(widths);
                bConsole(pagesize);
            }

            if (widths) {
                var percent = 100 / $("thead tr:first th:not([class='actions']):not([class='expCol']):not([class='ignoreSize'])", tableSelector).length;
                $("thead tr:first th:not([class='actions']):not([class='expCol']):not([class='ignoreSize'])", tableSelector).css("width", percent + "%");
            }
            
            if (drawCallBack == null || drawCallBack == "") {
                drawCallBack = function() { bConsole("Null Function Passed"); }
            }

            var tempTable = $(tableSelector).dataTable({
                aaSorting: defaultSort,
                aoColumnDefs: [
                        {bSortable: false, aTargets: noSort},
                        {
                                fnRender:
                                        function(oObj) {
                                                return fnSplitSearchTerm(oObj.aData[oObj.iDataColumn]);
                                        },
                                bUseRendered: false,
                                aTargets: splitSort
                        }
                ],
                bSortCellsTop: true,
                bProcessing: true,
                bPaginate: true,
                bLengthChange: false,
                bFilter: true,
                bSort: true,
                bInfo: true,
                bAutoWidth: false,
                bStateSave: true,
                fnStateSave: function (oSettings, oData) {
                    if (typeof(Storage) !== "undefined") {
                        sessionStorage.setItem(storageLocation, JSON.stringify(oData));
                    }
                },
                fnStateLoad: function (oSettings) {
                    if (typeof(Storage) !== "undefined") {
                        return JSON.parse(sessionStorage.getItem(storageLocation) || "null");
                    }
                },
                bSortClasses: false,
                iDisplayLength: pagesize,
                oLanguage: {
                    sZeroRecords: zero,
                    oPaginate: {
                            sFirst: "&lt;&lt;",
                            sLast: "&gt;&gt;",
                            sPrevious: "&lt;",
                            sNext: "&gt;"
                    }
                },
                fnDrawCallback: drawCallBack,
                sPaginationType: "full_numbers",
                sDom: layout,
                fnInitComplete: function() {
                    $(tableSelector).show();
                }
            });

            defineTableFilter(tableSelector, tempTable);

            return tempTable;

        }

    }

    function defineTableFilter(tableSelector, table) {

        $(tableSelector + " thead tr.search th input").keyup(function() {
            table.fnFilter(this.value, $(tableSelector + " thead tr.search th").index($(this).parent()));
        });

    }

    $.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw) {

        for(iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
            oSettings.aoPreSearchCols[ iCol ].sSearch = '';
        }
        oSettings.oPreviousSearch.sSearch = '';

        if (typeof bDraw === 'undefined') { bDraw = true; }
        if (bDraw) { this.fnDraw(); }

    }

    $.fn.dataTableExt.oApi.fnRestoreAllFilters = function (oSettings) {

        for(iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
            $("thead tr.search th:eq(" + iCol + ") :input").val(oSettings.aoPreSearchCols[ iCol ].sSearch.replace("^", "").replace("$", ""));
        }

    }

    function bConsole(message) {

        window.console && console.log(message);

    }
    
</script>