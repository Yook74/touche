import { Component, OnInit } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';

import { TableColumn } from '../../../models/table_column';
import { Clarification } from '../../../models/clarification';
import { ClarificationService } from '../../../services/model_services/clarification.service';
import { LiveClarificationDetailsComponent } from './details/details.component';
import { LiveClarificationRequestComponent } from './request/request.component';

@Component({
    templateUrl: './clarifications.component.html'
})
export class LiveClarificationsComponent {
    tableColumns: TableColumn[] = [
        { header: 'Question', dataField: 'question', displayIsComponent: false, component: null },
        { header: 'Response', dataField: 'response', displayIsComponent: false, component: null },
        { header: 'Time Submitted', dataField: 'submitTimestamp', displayIsComponent: false, component: null },
        { header: 'Time Answered', dataField: 'replyTimestamp', displayIsComponent: false, component: null },
        { header: 'Details', dataField: 'details', displayIsComponent: true, component: LiveClarificationDetailsComponent }
    ];
    clarifications: Clarification[];

    sorts = [
        {value: 'sort-0', viewValue: 'Submitted Time'},
        {value: 'sort-1', viewValue: 'Answered Time'}
    ];

    filters = [
        {value: 'filter-0', viewValue: 'General'},
        {value: 'filter-1', viewValue: 'Problem 1'},
        {value: 'filter-2', viewValue: 'Problem 2'},
        {value: 'filter-3', viewValue: 'Problem 3'}
    ];

    constructor(private service: ClarificationService, public dialog: MatDialog) { }

    ngOnInit() {
        this.clarifications = this.service.getMockData();
    }

    openDialog(): void {
        let dialogRef = this.dialog.open(LiveClarificationRequestComponent, {
            width: '',
            data: { }
        });

        dialogRef.afterClosed().subscribe(result => {
            console.log('The dialog was closed');
        });
    }

}
