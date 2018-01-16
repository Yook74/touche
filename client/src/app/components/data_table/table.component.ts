import { Component, Input, OnInit } from '@angular/core';
import { MatTableDataSource } from '@angular/material';
import { TableColumn } from '../../models/table_column';

@Component({
    selector: 'data-table',
    templateUrl: './table.component.html'
})
export class TableComponent implements OnInit {
    @Input() headers: string[];
    @Input() columns: TableColumn[];
    @Input() data: any[];
    dataSource: MatTableDataSource;

    constructor() { }

    ngOnInit() {
        this.headers = this.columns.map(c => c.header);
        this.dataSource = new MatTableDataSource(this.data);
    }
}
