

export const STATUS = {
    NORMAL: "Normal",
    BELOW_MINIMUM:"Below Min",
    BELOW_LTAS:"Below LTAs",
    ABOVE_LTAS:"Above LTAs",
    ABOVE_MAX:"Above Max",
    STABLE:"Stable",
    INCREASING:"Increasing",
    DECREASING:"Decreasing"
}

export const INITIAL_DATA = [
    { country: "Thailand", station: "Chiang Saen", B_name:"",code:"CSA", Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "20.27412033", longitude:"100.0885468"},
    { country: "Lao", station: "Luang Prabang",B_name:"", code:"LUA",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "19.89279938", longitude:"102.1341782" },
    { country: "Thailand", station: "Chiang Khan",B_name:"", code:"CKH",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "17.90026093", longitude:"101.6698914" },
    { country: "Lao", station: "Vientiane",B_name:"", code:"VIE",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "17.93098068", longitude:"102.6155624"},
    { country: "Thailand", station: "Nong Khai",B_name:"", code:"NON",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "17.8777504", longitude:"102.7166672"},
    { country: "Lao", station: "Paksane",B_name:"", code:"PAK",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "18.37340403", longitude:"103.6632097" },
    { country: "Thailand", station: "Nakhon Phanom",B_name:"", code:"NAK",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "17.623797", longitude:"104.517419"},
    { country: "Lao", station: "Thakhek",B_name:"", code:"THA",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "17.39453405", longitude:"104.8014" },
    { country: "Thailand", station: "Mukdahan",B_name:"", code:"MUK",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "16.58279991", longitude:"104.7331772" },
    { country: "Lao", station: "Savannakhet",B_name:"", code:"SAV",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "16.558", longitude:"104.744"},
    { country: "Thailand", station: "Khong Chiam",B_name:"", code:"KHO",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "15.32209015", longitude:"105.4934769"},
    { country: "Lao", station: "Pakse",B_name:"", code:"PKS",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "15.09976006", longitude:"105.8131866"},
    { country: "Cambodia", station: "Stung Treng",B_name:"", code:"STR",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "13.53250027", longitude:"105.9501877" },
    { country: "Cambodia", station: "Kratie",B_name:"", code:"KRA",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "12.48141003", longitude:"106.0176163"},
    { country: "Cambodia", station: "Kompong Cham",B_name:"", code:"KOM",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.994647", longitude:"105.468727" },
    { country: "Cambodia", station: "Phnom Penh (Bassac)",B_name:"", code:"PPB",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.568271", longitude:"104.9224426" },
    { country: "Cambodia", station: "Phnom Penh Port ",B_name:"", code:"PPP",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.57641", longitude:"104.92651" },
    { country: "Cambodia", station: "Koh Khel",B_name:"(Bassac)", code:"KOH",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.24203528", longitude:"105.0361553" },
    { country: "Cambodia", station: "Neak Luong",B_name:"", code:"NEA",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.26303677", longitude:"105.2801239" },
    { country: "Cambodia", station: "Prek Kdam",B_name:"(Tonel Sap)", code:"PRE",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "11.81116962", longitude:"104.806778" },
    { country: "Viet Nam", station: "Tan Chau",B_name:"", code:"TCH",Today: "",FlowThreshold:"",  Weekly: "", Trend: "" ,latitude: "10.80062008", longitude:"105.2480164", tooltip:true},
    { country: "Viet Nam", station: "Chau Doc",B_name:"", code:"CDO",Today: "",FlowThreshold:"",  Weekly: "", Trend: "",latitude: "10.7052803", longitude:"105.1335068", tooltip:true }
  ]

export const COUNTRY_LIST = ["China", "Cambodia", "Lao", "Thailand", "Viet Nam"]