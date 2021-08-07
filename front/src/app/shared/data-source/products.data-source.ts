import {CollectionViewer, DataSource} from "@angular/cdk/collections";
import {Product, ProductService} from "@shared";
import {BehaviorSubject, Observable, of} from "rxjs";
import {catchError, finalize} from "rxjs/operators";

export class ProductsDataSource implements DataSource<Product> {

  private productsSubject = new BehaviorSubject<Product[]>([]);
  private loadingSubject = new BehaviorSubject<boolean>(false);
  private nSubject = new BehaviorSubject<number>(0);

  public loading$ = this.loadingSubject.asObservable();
  public n = this.nSubject.asObservable();

  constructor(private coursesService: ProductService) {}

  connect(collectionViewer: CollectionViewer): Observable<Product[]> {
    return this.productsSubject.asObservable();
  }

  disconnect(collectionViewer: CollectionViewer): void {
    this.productsSubject.complete();
    this.loadingSubject.complete();
  }

  loadProducts(uuid: string, filter = '',
              orderBy  = 'asc', pageIndex = 0, pageSize = 3) {

    this.loadingSubject.next(true);

    this.coursesService.findProducts(uuid, filter, orderBy,
      pageIndex, pageSize).pipe(
      catchError(() => of([])),
      finalize(() => this.loadingSubject.next(false))
    )
      .subscribe((products: any ) => {
        this.nSubject.next(products.n);
        return this.productsSubject.next(products.data)
      });
  }
}
