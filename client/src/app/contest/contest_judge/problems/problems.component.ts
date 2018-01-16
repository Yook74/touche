import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Problem } from '../../../models/problem';
import { ProblemService } from '../../../services/model_services/problem.service';

@Component({
    templateUrl: './problems.component.html'
})
export class JudgeProblemsComponent implements OnInit {
    tableColumns: TableColumn[] = [
        { header: 'Name', dataField: 'name', displayIsComponent: false, componentName: '' },
        { header: 'Attachments', dataField: 'id', displayIsComponent: true, componentName: '' }
    ];
    problems: Problem[];

    constructor(private service: ProblemService) { }

    ngOnInit() {
        this.problems = this.service.getMockData();
    }
}
