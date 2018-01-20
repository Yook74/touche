import { Component, OnInit } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';

import { TableColumn } from '../../../../models/table_column';
import { Category } from '../../../../models/site';
import { CategoryService } from '../../../../services/model_services/category.service';
import { AdminCategoryEditComponent } from './edit/edit.component';
import { AdminCategoryDeleteComponent } from './delete/delete.component';
import { AdminCategoryAddComponent } from './add/add.component';


@Component({
    templateUrl: './categories.component.html'
})
export class AdminCategoriesComponent {

    tableColumns: TableColumn[] = [
        { header: 'Name', dataField: 'name', displayIsComponent: false, component: null },
        { header: 'Edit', dataField: 'id', displayIsComponent: true, component: AdminCategoryEditComponent },
        { header: 'Delete', dataField: 'id', displayIsComponent: true, component: AdminCategoryDeleteComponent }
    ];
    categories: Category[];


    constructor(private service: CategoryService, public dialog: MatDialog) { }

    ngOnInit() {
        this.categories = this.service.getMockData();
    }

    addCategory(): void {
        let dialogRef = this.dialog.open(AdminCategoryAddComponent, {
            width: '',
            data: { }
        });

        dialogRef.afterClosed().subscribe(result => {
            console.log('The dialog was closed');
        });
    }


}
