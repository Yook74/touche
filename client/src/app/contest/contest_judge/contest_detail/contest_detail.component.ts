import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Site } from '../../../models/site';
import { SiteService } from '../../../services/model_services/site.service';
import { JudgeContestDetailTeamsComponent } from './teams/teams.component';
import { JudgeContestDetailStatusComponent } from './status/status.component';
import { JudgeContestDetailTimeRemainingComponent } from './time_remaining/time_remaining.component';

@Component({
    templateUrl: './contest_detail.component.html'
})
export class JudgeContestDetailComponent {
    tableColumns: TableColumn[] = [
        { header: 'Site', dataField: 'name', displayIsComponent: false, component: null },
        { header: 'Teams', dataField: 'id', displayIsComponent: true, component: JudgeContestDetailTeamsComponent },
        { header: 'Time Remaining', dataField: 'id', displayIsComponent: true, component: JudgeContestDetailTimeRemainingComponent },
        { header: 'Status', dataField: 'id', displayIsComponent: true, component: JudgeContestDetailStatusComponent }
    ];
    sites: Site[];

    constructor(private service: SiteService) { }

    ngOnInit() {
        this.sites = this.service.getMockData();
    }
}
