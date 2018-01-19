import { Component, Input, Inject } from '@angular/core';
import { FieldComponent } from '../../../../../components/data_table/field.component';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';


@Component({
    selector: 'admin-category-add',
    templateUrl: './add.component.html'
})
export class AdminCategoryAddComponent {

    someValue: boolean = false;

    constructor (
      public dialogRef: MatDialogRef<AdminCategoryAddComponent>,
      @Inject(MAT_DIALOG_DATA) public data: any) { }

    onNoClick(): void {
      this.dialogRef.close(this.someValue);
    }
}
