import { Component, Input, Inject } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';


@Component({
    selector: 'admin-problem-add',
    templateUrl: './add.component.html'
})
export class AdminProblemAddComponent {

    someValue: boolean = false;

    constructor (
      public dialogRef: MatDialogRef<AdminProblemAddComponent>,
      @Inject(MAT_DIALOG_DATA) public data: any) { }

    onNoClick(): void {
      this.dialogRef.close(this.someValue);
    }
}
