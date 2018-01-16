import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Clarification } from '../../../models/clarification';
import { ClarificationService } from '../../../services/model_services/clarification.service';

@Component({
    templateUrl: './clarifications.component.html'
})
export class JudgeClarificationsComponent {
    tableColumns: TableColumn[] = [
        { header: 'Problem', dataField: 'problemId', displayIsComponent: false, componentName: '' },
        { header: 'Question', dataField: 'question', displayIsComponent: false, componentName: '' },
        { header: 'Response', dataField: 'response', displayIsComponent: false, componentName: '' },
        { header: 'Time Submitted', dataField: 'submitTimestamp', displayIsComponent: false, componentName: '' },
        { header: 'Time Answered', dataField: 'replyTimestamp', displayIsComponent: false, componentName: '' },
        { header: 'Answer', dataField: 'details', displayIsComponent: true, componentName: '' }
    ];
    clarifications: Clarification[];

    constructor(private service: ClarificationService) { }

    ngOnInit() {
        this.clarifications = this.service.getMockData();
    }
}
