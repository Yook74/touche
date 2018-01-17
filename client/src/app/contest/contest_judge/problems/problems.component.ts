import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Problem } from '../../../models/problem';
import { ProblemService } from '../../../services/model_services/problem.service';
import { JudgeProblemAttachmentsComponent } from './attachments/attachments.component';

@Component({
    templateUrl: './problems.component.html'
})
export class JudgeProblemsComponent implements OnInit {
    tableColumns: TableColumn[] = [
        { header: 'Name', dataField: 'name', displayIsComponent: false, component: null },
        { header: 'Attachments', dataField: 'id', displayIsComponent: true, component: JudgeProblemAttachmentsComponent }
    ];
    problems: Problem[];

    constructor(private service: ProblemService) { }

    ngOnInit() {
        this.problems = this.service.getMockData();
    }
}
