<!DOCTYPE html>
<html>
    <head>
        <title>POPO</title>
        <link rel="stylesheet" href="styles/kendo.common.min.css" />
        <link rel="stylesheet" href="styles/kendo.default.min.css" />
        <link rel="stylesheet" href="styles/kendo.dataviz.min.css" />
        <link rel="stylesheet" href="styles/kendo.dataviz.default.min.css" />

        <script src="js/jquery.min.js"></script>
        <script src="js/jszip.min.js"></script>
        <script src="js/kendo.all.min.js"></script>
    </head>
    <body>
        <div id="example">
            <div id="grid" style="width: 900px"></div>
            <script>
                $("#grid").kendoGrid({
                    toolbar: ["excel"],
                    excel: {
                        fileName: "Kendo UI Grid Export.xlsx",
                        proxyURL: "http://demos.telerik.com/kendo-ui/service/export",
                        filterable: true
                    },
                    dataSource: {
                        type: "odata",
                        transport: {
                            read: "http://demos.telerik.com/kendo-ui/service/Northwind.svc/Products"
                        },
                        schema: {
                            model: {
                                fields: {
                                    UnitsInStock: {type: "number"},
                                    ProductName: {type: "string"},
                                    UnitPrice: {type: "number"},
                                    UnitsOnOrder: {type: "number"},
                                    UnitsInStock: {type: "number"}
                                }
                            }
                        },
                        pageSize: 7,
                        group: {
                            field: "UnitsInStock", aggregates: [
                                {field: "ProductName", aggregate: "count"},
                                {field: "UnitPrice", aggregate: "sum"},
                                {field: "UnitsOnOrder", aggregate: "average"},
                                {field: "UnitsInStock", aggregate: "count"}
                            ]
                        },
                        aggregate: [
                            {field: "ProductName", aggregate: "count"},
                            {field: "UnitPrice", aggregate: "sum"},
                            {field: "UnitsOnOrder", aggregate: "average"},
                            {field: "UnitsInStock", aggregate: "min"},
                            {field: "UnitsInStock", aggregate: "max"}
                        ]
                    },
                    sortable: true,
                    pageable: true,
                    groupable: true,
                    filterable: true,
                    columnMenu: true,
                    reorderable: true,
                    resizable: true,
                    columns: [
                        {width: 300, locked: true, field: "ProductName", title: "Product Name", aggregates: ["count"], footerTemplate: "Total Count: #=count#", groupFooterTemplate: "Count: #=count#"},
                        {width: 300, field: "UnitPrice", title: "Unit Price", aggregates: ["sum"]},
                        {width: 300, field: "UnitsOnOrder", title: "Units On Order", aggregates: ["average"], footerTemplate: "Average: #=average#",
                            groupFooterTemplate: "Average: #=average#"},
                        {width: 300, field: "UnitsInStock", title: "Units In Stock", aggregates: ["min", "max", "count"], footerTemplate: "Min: #= min # Max: #= max #",
                            groupHeaderTemplate: "Units In Stock: #= value # (Count: #= count#)"}
                    ]
                });

            </script>
        </div>


    </body>
</html>