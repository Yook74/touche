import { Component, OnInit } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';

import { TableColumn } from '../../../models/table_column';
import { Team } from '../../../models/team';
import { TeamService } from '../../../services/model_services/team.service';
import { AdminTeamEditComponent } from './edit/edit.component';
import { AdminTeamDeleteComponent } from './delete/delete.component';
import { AdminTeamCategoryComponent } from './category/category.component';
import { AdminTeamAddComponent } from './add/add.component';

@Component({
    templateUrl: './teams.component.html'
})
export class AdminTeamsComponent {
    tableColumns: TableColumn[] = [
        { header: 'Name', dataField: 'name', displayIsComponent: false, component: null },
        { header: 'Organization', dataField: 'organization', displayIsComponent: false, component: null },
        { header: 'Coach', dataField: 'coachName', displayIsComponent: false, component: null },
        { header: 'Site', dataField: 'id', displayIsComponent: false, component: null },
        { header: 'Categories', dataField: 'id', displayIsComponent: true, component: AdminTeamCategoryComponent },
        { header: 'Edit', dataField: 'id', displayIsComponent: true, component: AdminTeamEditComponent },
        { header: 'Delete', dataField: 'id', displayIsComponent: true, component: AdminTeamDeleteComponent }
    ];
    teams: Team[];

    filters = [
        {value: 'filter-0', viewValue: 'All Categories'},
        {value: 'filter-1', viewValue: 'Category 1'},
        {value: 'filter-2', viewValue: 'Category 2'},
        {value: 'filter-3', viewValue: 'Category 3'}
    ];


    constructor(private service: TeamService, public dialog: MatDialog) { }

    ngOnInit() {
        this.teams = this.service.getMockData();
    }

    addTeam(): void {
        let dialogRef = this.dialog.open(AdminTeamAddComponent, {
            width: '',
            data: { }
        });

        dialogRef.afterClosed().subscribe(result => {
            console.log('The dialog was closed');
        });
    }


}
