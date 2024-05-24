import type { LocationQuery } from "vue-router";

export const getParamsUrlString = (arrOfParams:{name:string,item:string}[]):string =>{
    const groupedItems: { [key: string]: string[] } = {};

    
    arrOfParams.forEach(({ name, item }) => {
        if (!groupedItems[name]) {
            groupedItems[name] = [];
        }
        groupedItems[name].push(item);
    });

    const result = Object.entries(groupedItems)
        .map(([name, items]) => `${name}=${items.join('%')}`)
        .join('&');

    return result;
}
export const getParamsArrayFromObj = (params: LocationQuery): Array<{ name: string, item: string }> => {
    let result: { name: string, item: string }[] = [];

    Object.keys(params).forEach(function (key) {
        const str = params[key]?.toString();
        const paramValues = str?.split("%");
        
        if (paramValues) {
            const mappedValues = paramValues.map((value) => {
                return { name: key, item: value };
            });
            result = result.concat(mappedValues);
        }
    });

    return result;
}

export const getFilteredData = async (path: string | string[], params: string = ""): Promise<{category_name:string , products:Product[] ,filters:Filter[]}> => {
    try {
        const { data: productsRef } = await useFetch<{category_name:string , products:Product[] ,filters:Filter[]}>(`http://localhost:8000/products/filter/${path}${params !== "" ? `?${params}` : ""}`);
        const products = productsRef.value; 
        if (products) {
            return products; 
        }else{
            return {category_name:"",products:[] ,filters:[]};
        }

    } catch (error) {
        console.error("Failed to fetch data:", error);
        throw new Error("Failed to fetch data");
    }
}
export function resetFileInput() {
    const fileInput = document.getElementById('file') as HTMLInputElement;
    if (fileInput) {
      fileInput.value = ''; 
    }
  }
