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

    filters_submission = [
        {value: 'filter-0', viewValue: 'New Submissions'},
        {value: 'filter-1', viewValue: 'Judged Submissions'}
    ];

    filters_problem = [
        {value: 'filter-0', viewValue: 'All Problems'},
        {value: 'filter-1', viewValue: 'Problem 1'},
        {value: 'filter-2', viewValue: 'Problem 2'},
        {value: 'filter-3', viewValue: 'Problem 3'}
    ];

    filters_team = [
        {value: 'filter-0', viewValue: 'All Teams'},
        {value: 'filter-1', viewValue: 'Team 1'},
        {value: 'filter-2', viewValue: 'Team 2'},
        {value: 'filter-3', viewValue: 'Team 3'}
    ];

    constructor(private service: SubmissionService) { }

    ngOnInit() {
        this.submissions = this.service.getMockData();
    }
}
