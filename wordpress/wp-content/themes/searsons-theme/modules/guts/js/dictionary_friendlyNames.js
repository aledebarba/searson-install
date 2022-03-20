/**
 * defines friendly names for common fields values in the database
 * 
 */

const dictionary = [
    { field: 'title-asc', label: 'Names A to Z' },
    { field: 'title-desc', label: 'Names Z to A' },
    { field: 'price-asc', label: 'Prices Low to High' },
    { field: 'price-desc', label: 'Prices High to Low' },
    { field: 'created-asc', label: 'Newest First' },
    { field: 'sort-default', label: 'Default' },
    { field: 'sweetness-f0-t99999', label: 'Sweetness - All' },
    { field: 'sweetness-f0-t1', label: 'Sweetness - Dry' },
    { field: 'sweetness-f1-t5', label: 'Sweetness - Off Dry' },
    { field: 'sweetness-f5-t7', label: 'Medium Sweetness' },
    { field: 'sweetness-f7-t99999', label: 'Sweet' },
    { field: 'sweetness-f0-t99999', label: 'Sweetness - All' },
    { field: 'price-f0-t15', label: '€0 to €15' },
    { field: 'price-f15-t25', label: '€15 to €25' },
    { field: 'price-f25-t50', label: '€25 to €50' },
    { field: 'price-f50-t100', label: '€50 to €100' },
    { field: 'price-f100-t99999', label: '€100 or more' },
    { field: 'price-f0-t99999', label: 'All' },
    { field: 'maturity-1', label: 'Maturity 1: Drink Now' },
    { field: 'maturity-2', label: 'Maturity 2: Will Improve' },
    { field: 'maturity-3', label: 'Maturity 3: Lay Down' },
    { field: 'organic', label: 'Organic' },
    { field: 'vegan', label: 'Vegan' } 
  ]
  
export const friendlyName = dictionary;

export function getFriendlyName(field) {
    const item = dictionary.find(item => item.field === field);
    return item ? item.label : field;
}