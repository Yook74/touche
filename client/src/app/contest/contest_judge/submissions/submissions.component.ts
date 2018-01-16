import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Submission } from '../../../models/submission';
import { SubmissionService } from '../../../services/model_services/submission.service';
import { JudgeSubmissionJudgeComponent } from './judge/judge.component';

@Component({
    templateUrl: './submissions.component.html'
})
export class JudgeSubmissionsComponent {
    tableColumns: TableColumn[] = [
        { header: 'Team', dataField: 'teamId', displayIsComponent: false, component: null },
        { header: 'Problem', dataField: 'problemId', displayIsComponent: false, component: null },
        { header: 'Attempts', dataField: 'attemps', displayIsComponent: false, component: null },
        { header: 'Response', dataField: 'responseId', displayIsComponent: false, component: null },
        { header: 'Time Submitted', dataField: 'timestamp', displayIsComponent: false, component: null },
        { header: 'Judge', dataField: 'judge', displayIsComponent: true, component: JudgeSubmissionJudgeComponent }
    ];
    submissions: Submission[];

    constructor(private service: SubmissionService) { }

    ngOnInit() {
        this.submissions = this.service.getMockData();
    }
}
