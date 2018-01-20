import { Component, Input, Inject } from '@angular/core';
import { FieldComponent } from '../../../../../components/data_table/field.component';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';


@Component({
    selector: 'admin-site-add',
    templateUrl: './add.component.html'
})
export class AdminSiteAddComponent {

    someValue: boolean = false;

    constructor (
      public dialogRef: MatDialogRef<AdminSiteAddComponent>,
      @Inject(MAT_DIALOG_DATA) public data: any) { }

    onNoClick(): void {
      this.dialogRef.close(this.someValue);
    }
}
