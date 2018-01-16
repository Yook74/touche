import { Component, Input, OnInit, ViewChild } from '@angular/core';
import { MatTableDataSource, MatSort } from '@angular/material';
import { TableColumn } from '../../models/table_column';

@Component({
    selector: 'data-table',
    templateUrl: './table.component.html'
})
export class TableComponent implements OnInit {
    @Input() headers: string[];
    @Input() columns: TableColumn[];
    @Input() data: any[];
    dataSource: MatTableDataSource<any>;


    @ViewChild(MatSort) sort: MatSort;

    /**
    * Set the sort after the view init since this component will
    * be able to query its view for the initialized sort.
    */
    ngAfterViewInit() {
        this.dataSource.sort = this.sort;
    }

    constructor() { }

    ngOnInit() {
        this.headers = this.columns.map(c => c.header);
        this.dataSource = new MatTableDataSource<any>(this.data);
    }
}
