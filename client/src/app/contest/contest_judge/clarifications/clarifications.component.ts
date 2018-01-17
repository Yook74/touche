import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Clarification } from '../../../models/clarification';
import { ClarificationService } from '../../../services/model_services/clarification.service';
import { JudgeClarificationAnswerComponent } from './answer/answer.component';

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

    constructor(private service: ClarificationService) { }

    ngOnInit() {
        this.clarifications = this.service.getMockData();
    }
}
