import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Response } from '../../models/response';

@Injectable()
export class ResponseService {
    constructor(private baseService: BaseService) { }
}