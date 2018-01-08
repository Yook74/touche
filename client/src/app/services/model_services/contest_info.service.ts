import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { ContestInfo } from '../../models/contest_info';

@Injectable()
export class ContestInfoService {
    constructor(private baseService: BaseService) { }
}