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
        { header: 'Name', dataField: 'name', displayIsComponent: false, component: null },
        { header: 'Organization', dataField: 'organization', displayIsComponent: false, component: null },
        { header: 'Coach', dataField: 'coachName', displayIsComponent: false, component: null },
        { header: 'Site', dataField: 'id', displayIsComponent: false, component: null },
        { header: 'Categories', dataField: 'id', displayIsComponent: true, component: AdminTeamCategoriesComponent },
        { header: 'Edit', dataField: 'id', displayIsComponent: true, component: AdminTeamEditComponent },
        { header: 'Delete', dataField: 'id', displayIsComponent: true, component: AdminTeamDeleteComponent }
    ];
    teams: Team[];

    constructor(private service: TeamService) { }

    ngOnInit() {
        this.teams = this.service.getMockData();
    }
}
