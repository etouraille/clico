import { Component, OnInit } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-variant-list',
  templateUrl: './variant-list.component.html',
  styleUrls: ['./variant-list.component.css']
})
export class VariantListComponent implements OnInit {

  variants : [];

  displayedColumns : any = ['name', 'labels'];

  constructor(
    private http: HttpClient,
  ) { }

  ngOnInit(): void {

    this.http.get('/api/variant-product').subscribe((variants: any) => {
      this.variants = variants;
    })

  }

}
