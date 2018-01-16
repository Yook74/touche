import {Component, ViewChild, Input} from '@angular/core';
import {MatTableDataSource, MatSort} from '@angular/material';

@Component({
    selector: 'data-table',
    templateUrl: './table.component.html'
})
export class TableComponent {
    @Input() headers: string[];

    dataSource = new MatTableDataSource(TABLE_DATA);

    @ViewChild(MatSort) sort: MatSort;

  /**
   * Set the sort after the view init since this component will
   * be able to query its view for the initialized sort.
   */
    ngAfterViewInit() {
    this.dataSource.sort = this.sort;
    }
}


export interface Problem {
    name: string;
    attachments: string;
    attempts: number;
    submit: string;
}

const TABLE_DATA: Problem[] = [
  {name: 'Hydrogen', attachments: 'HTML PDF', attempts: 5, submit: 'Submit'},
  {name: 'Helium', attachments: 'HTML PDF', attempts: 5, submit: 'Submit'},
  {name: 'Lithium', attachments: 'HTML PDF', attempts: 5, submit: 'Submit'},
  {name: 'Beryllium', attachments: 'HTML PDF', attempts: 5, submit: 'Submit'},
  {name: 'Boron', attachments: 'HTML PDF', attempts: 5, submit: 'Submit'},
  {name: 'Carbon', attachments: 'HTML PDF', attempts: 5, submit: 'Submit'},
  {name: 'Nitrogen', attachments: 'HTML PDF', attempts: 5, submit: 'Submit'},
  {name: 'Oxygen', attachments: 'HTML PDF', attempts: 5, submit: 'Submit'}
];
