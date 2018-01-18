import { Component, OnInit } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';

import { TableColumn } from '../../../models/table_column';
import { Clarification } from '../../../models/clarification';
import { ClarificationService } from '../../../services/model_services/clarification.service';
import { JudgeClarificationAnswerComponent } from './answer/answer.component';
import { JudgeClarificationMakeComponent } from './make/make.component';

@Component({
    templateUrl: './clarifications.component.html'
})
export class JudgeClarificationsComponent {
    tableColumns: TableColumn[] = [
        { header: 'Problem', dataField: 'problemId', displayIsComponent: false, component: null },
        { header: 'Question', dataField: 'question', displayIsComponent: false, component: null },
        { header: 'Response', dataField: 'response', displayIsComponent: false, component: null },
        { header: 'Time Submitted', dataField: 'submitTimestamp', displayIsComponent: false, component: null },
        { header: 'Time Answered', dataField: 'replyTimestamp', displayIsComponent: false, component: null },
        { header: 'Answer', dataField: 'details', displayIsComponent: true, component: JudgeClarificationAnswerComponent }
    ];
    clarifications: Clarification[];

    filters = [
        {value: 'filter-0', viewValue: 'Unanswered'},
        {value: 'filter-1', viewValue: 'Answered'}
    ];

    sorts = [
        {value: 'sort-0', viewValue: 'Submitted Time'},
        {value: 'sort-1', viewValue: 'Answered Time'}
    ];

    constructor(private service: ClarificationService, public dialog: MatDialog) { }

    ngOnInit() {
        this.clarifications = this.service.getMockData();
    }

    openDialog(): void {
        let dialogRef = this.dialog.open(JudgeClarificationMakeComponent, {
            width: '',
            data: { }
        });

        dialogRef.afterClosed().subscribe(result => {
            console.log('The dialog was closed');
        });
    }

}
