import { Component, Input, Inject } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';


@Component({
    selector: 'admin-team-add',
    templateUrl: './add.component.html'
})
export class AdminTeamAddComponent {

    someValue: boolean = false;

    categories = [
        {value: 'category-1', viewValue: 'Category 1'},
        {value: 'category-2', viewValue: 'Category 2'},
        {value: 'category-3', viewValue: 'Category 3'}
    ];

    sites = [
        {value: 'site-1', viewValue: 'Site 1'},
        {value: 'site-2', viewValue: 'Site 2'},
        {value: 'site-3', viewValue: 'Site 3'}
    ];

    constructor (
      public dialogRef: MatDialogRef<AdminTeamAddComponent>,
      @Inject(MAT_DIALOG_DATA) public data: any) { }

    onNoClick(): void {
      this.dialogRef.close(this.someValue);
    }
}
