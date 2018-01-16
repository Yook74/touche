import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Team } from '../../../models/team';
import { TeamService } from '../../../services/model_services/team.service';
import { AdminTeamEditComponent } from './edit/edit.component';
import { AdminTeamDeleteComponent } from './delete/delete.component';
import { AdminTeamCategoriesComponent } from './categories/categories.component';

@Component({
    templateUrl: './teams.component.html'
})
export class AdminTeamsComponent {
    tableColumns: TableColumn[] = [
        { header: 'Name', dataField: 'name', displayIsComponent: false, componentName: '' },
        { header: 'Organization', dataField: 'organization', displayIsComponent: false, componentName: '' },
        { header: 'Coach', dataField: 'coachName', displayIsComponent: false, componentName: '' },
        { header: 'Site', dataField: 'id', displayIsComponent: false, componentName: '' },
        { header: 'Categories', dataField: 'id', displayIsComponent: true, componentName: AdminTeamCategoriesComponent },
        { header: 'Edit', dataField: 'id', displayIsComponent: true, componentName: AdminTeamEditComponent },
        { header: 'Delete', dataField: 'id', displayIsComponent: true, componentName: AdminTeamDeleteComponent }
    ];
    teams: Team[];

    constructor(private service: TeamService) { }

    ngOnInit() {
        this.teams = this.service.getMockData();
    }
}
