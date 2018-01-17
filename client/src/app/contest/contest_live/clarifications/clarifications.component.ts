import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Clarification } from '../../../models/clarification';
import { ClarificationService } from '../../../services/model_services/clarification.service';
import { LiveClarificationDetailsComponent } from './details/details.component';

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

    constructor(private service: ClarificationService) { }

    ngOnInit() {
        this.clarifications = this.service.getMockData();
    }
}
