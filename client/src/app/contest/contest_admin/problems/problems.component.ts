import { Component, OnInit } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';

import { TableColumn } from '../../../models/table_column';
import { Problem } from '../../../models/problem';
import { ProblemService } from '../../../services/model_services/problem.service';
import { AdminProblemAttachmentsComponent } from './attachments/attachments.component';
import { AdminProblemDataSetsComponent } from './data_sets/data_sets.component';
import { AdminProblemEditComponent } from './edit/edit.component';
import { AdminProblemDeleteComponent } from './delete/delete.component';
import { AdminProblemAddComponent } from './add/add.component';

@Component({
    templateUrl: './problems.component.html'
})
export class AdminProblemsComponent {
    tableColumns: TableColumn[] = [
        { header: 'Name', dataField: 'name', displayIsComponent: false, component: null },
        { header: 'Location', dataField: 'location', displayIsComponent: false, component: null },
        { header: 'Attachments', dataField: 'id', displayIsComponent: true, component: AdminProblemAttachmentsComponent },
        { header: 'Data Sets', dataField: 'id', displayIsComponent: true, component: AdminProblemDataSetsComponent },
        { header: 'Edit', dataField: 'id', displayIsComponent: true, component: AdminProblemEditComponent },
        { header: 'Delete', dataField: 'id', displayIsComponent: true, component: AdminProblemDeleteComponent }
    ];
    problems: Problem[];

    constructor(private service: ProblemService, public dialog: MatDialog) { }

    ngOnInit() {
        this.problems = this.service.getMockData();
    }

    openDialog(): void {
        let dialogRef = this.dialog.open(AdminProblemAddComponent, {
            width: '',
            data: { }
        });

        dialogRef.afterClosed().subscribe(result => {
            console.log('The dialog was closed');
        });
    }


}
