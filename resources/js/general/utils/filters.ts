export const formatCurrency = (value: number): string => {
    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    });
    return formatter.format(value / 100);
};
