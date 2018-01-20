import { Component, OnInit } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';

import { TableColumn } from '../../../../models/table_column';
import { Site } from '../../../../models/site';
import { SiteService } from '../../../../services/model_services/site.service';
import { AdminSiteEditComponent } from './edit/edit.component';
import { AdminSiteDeleteComponent } from './delete/delete.component';
import { AdminSiteAddComponent } from './add/add.component';


@Component({
    templateUrl: './sites.component.html'
})
export class AdminSitesComponent {

    tableColumns: TableColumn[] = [
        { header: 'Name', dataField: 'name', displayIsComponent: false, component: null },
        { header: 'Edit', dataField: 'id', displayIsComponent: true, component: AdminSiteEditComponent },
        { header: 'Delete', dataField: 'id', displayIsComponent: true, component: AdminSiteDeleteComponent }
    ];
    sites: Site[];


    constructor(private service: SiteService, public dialog: MatDialog) { }

    ngOnInit() {
        this.sites = this.service.getMockData();
    }

    addSite(): void {
        let dialogRef = this.dialog.open(AdminSiteAddComponent, {
            width: '',
            data: { }
        });

        dialogRef.afterClosed().subscribe(result => {
            console.log('The dialog was closed');
        });
    }


}
