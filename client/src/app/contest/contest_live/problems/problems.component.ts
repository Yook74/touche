import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Problem } from '../../../models/problem';
import { ProblemService } from '../../../services/model_services/problem.service';
import { ProblemAttachmentsComponent } from './attachments/attachments.component';

@Component({
    templateUrl: './problems.component.html'
})
export class LiveProblemsComponent implements OnInit {
    tableColumns: TableColumn[] = [
        { header: 'Name', dataField: 'name', displayIsComponent: false, component: null },
        { header: 'Attachments', dataField: 'id', displayIsComponent: true, component: ProblemAttachmentsComponent },
        { header: 'Attempts', dataField: 'id', displayIsComponent: false, component: null },
        { header: 'Submit', dataField: 'id', displayIsComponent: true, component: ProblemAttachmentsComponent }
    ];
    problems: Problem[];

    constructor(private service: ProblemService) { }

    ngOnInit() {
        this.problems = this.service.getMockData();
    }
}
