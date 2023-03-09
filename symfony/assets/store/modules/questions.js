import { 
  SET_QUESTIONS, 
  SET_RESULT_QUESTIONS, 
  SET_LOADER_TOGGLE,
  SET_RESULT_TICKET_USER,
  SET_LOADER_STATUS
} from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  // questions: [
  //   { 
  //     id: 1,
  //     title: "dfgdfgdfg",
  //     type_responses : "one",
  //     image: null
  //   },
  //   { 
  //     id: 2,
  //     title: "nfffffff",
  //     type_responses : "one",
  //     image: null
  //   },
  //   { 
  //     id: 3,
  //     title: "ertert",
  //     type_responses : "one",
  //     image: null
  //   },
  // ],
  question:{},

  questions:[
      {title:"На кого распространяются Правила по ",
      variant:["На работников",
      "На организаций",
      "электротехнического",
      "независимо"], 
      id: 100,
      "type":{"title":"conformity"},
      "subTitle":[
        "На работников организаций независимо от форм ",
        "На работников из числа электротехнического",
        "На работников всех организаций независимо "
      ]
      },
      {title:"На кого распространяются Правила по ",
      variant:["На работников промышленных предприятий",
      "На работников организаций независимо от форм ",
      "На работников из числа электротехнического",
      "На работников всех организаций независимо "], 
      id: 1,
      "type":{"title":"order"}},
      
      {title:"При каком условии работники, не обслуживающие электроустановки, могут допускаться в РУ до 1000 В? ",
      variant:["В сопровождении оперативного персонала, обслуживающего данную электроустановку, имеющего группу IV, либо работника, имеющего право единоличного осмотра",
      "*В сопровождении оперативного персонала, обслуживающего данную электроустановку, имеющего группу III, либо работника, имеющего право единоличного осмотра",
      "В сопровождении опытного работника из числа ремонтного персонала, имеющего группу по электробезопасности не ниже V"], 
      id: 2,
      "type":{"title":"checkbox"}},
      
      {title:"Кто дает разрешение на снятие напряжения при несчастных случаях для освобождения пострадавшего от действия электрического тока?",
      variant:["Разрешение дает оперативный персонал энергообъекта",
      "Разрешение дает вышестоящий оперативный персонал",
      "Разрешение дает административно-технический персонал",
      "*Предварительного разрешения оперативного персонала не требуется. Напряжение должно быть снято немедленно"], 
      id: 3,
      "type":{"title":"checkbox"}},
      
      {title:"В каком случае нарушен порядок хранения и выдачи ключей?",
      variant:["Ключи от электроустановок должны быть пронумерованы и храниться в запираемом ящике. Один комплект должен быть запасным",
      "Выдача ключей должна быть заверена подписью работника, выдавшего ключ, а также подписью работника, получившего ключ",
      "Ключи от электроустановок , оперативное обслуживание которых осуществляется круглосуточно оперативным персоналом,  должны передаваться по смене с оформлением в оперативном журнале",
      "*Допускается возвращать ключи от электроустановок оперативному персоналу в течение трех дней после полного окончания работ"], 
      id: 4,
      "type":{"title":"checkbox"}},
      
      {title:"Сколько экземпляров наряда – допуска должно оформляться? ",
      variant:["Достаточно одного",
      "*Наряд оформляется в двух экземплярах, а при передаче по телефону - в трех",
      "Наряд оформляется в трех экземплярах"], 
      id: 5,
      "type":{"title":"radio"}},
      
      {title:"Допускается ли оформлять наряд-допуск в электронном виде? ",
      variant:["Наряд может быть выписан только от руки на специальном бланке установленной формы",
      "Наряд допускается оформлять только в виде телефонограммы или радиограммы",
      "Допускается, по усмотрению руководителя, в зависимости от расположения диспетчерского пункта",
      "*Разрешено оформлять наряд в электронном виде и передавать по электронной почте"], 
      id: 6,
      "type":{"title":"radio"}},
      
      {title:"Кто имеет право на продление наряда- допуска? ",
      variant:["*Только работник, выдавший наряд, или имеющий право выдачи наряда в данной электроустановке",
      "Ответственный руководитель работ в данной электроустановке",
      "Ответственный за электрохозяйство структурного подразделения",
      "Руководитель объекта, на котором проводятся работы"], 
      id: 7,
      "type":{"title":"radio"}},
      
      {title:"Каким способом может быть передано разрешение на продление наряда -допуска? ",
      variant:["Только по телефону дежурному диспетчеру с записью в оперативном журнале",
      "Только с нарочным допускающему с последующей записью в строке наряда Отдельные указания",
      "Только по радио производителю работ с последующей росписью в таблице наряда-допуска Разрешение на подготовку рабочих мест и на допуск к выполнению работ",
      "*По телефону,  радио или с нарочным допускающему, ответственному руководителю или производителю работ. В этом случае допускающий, ответственный руководитель или производитель работ за своей подписью указывает в наряде фамилию и инициалы работника, продлившего наряд"], 
      id: 8,
      "type":{"title":"radio"}},
      
      {title:"После какого срока могут быть уничтожены наряды-допуски, работы по которым полностью закончены и не имели место аварии, инциденты и несчастные случаи? ",
      variant:["По истечении 15 суток",
      "*По истечении  1 года",
      "По истечении 45 суток",
      "По истечении 10 суток"], 
      id: 9,
      "type":{"title":"radio"}},
      
      {title:"Каким образом в электроустановках ведется учет производства работ по нарядам-доускам и распоряжениям? ",
      variant:["В журнале проведения целевого инструктажа",
      "В журнале произвольной формы",
      "В папке действующих нарядов",
      "*В журнале учета работ по нарядам и распоряжениям"], 
      id: 10,
      "type":{"title":""}},
      
      {title:"Какие требования установлены Правилами по охране труда при эксплуатации электроустановок по ведению журнала учета работ по нарядам-допускам и распоряжениям? ",
      variant:["Форму журнала определяет руководитель структурного подразделения  в зависимости от специфики деятельности",
      "*Независимо от принятого в организации порядка учета работ по нарядам и распоряжениям факт допуска к работе должен быть зарегистрирован записью в оперативном документе",
      "Ведение журнала учета работ по нарядам и распоряжениям  не допускается в электронной форме с применением автоматизированных систем и использованием электронной подписи"], 
      id: 11,
      "type":{"title":""}},
      
      {title:"Допустимо ли пребывание одного или нескольких членов бригады отдельно от производителя работ, в случае рассредоточения членов бригады по разным рабочим местам? ",
      variant:["Недопустимо в любом случае",
      "Допустимо в любом случае",
      "*Допустимо, при наличии у членов бригады III группы по электробезопасности",
      "Допустимо, при проведении соответствующего инструктажа"], 
      id: 12,
      "type":{"title":""}},
      
      {title:"Кому разрешается работать единолично в электроустановках напряжением до 1000 В, расположенных в помещениях, кроме особо опасных и в особо неблагоприятных условиях в отношении поражения людей электрическим током? ",
      variant:["Работнику, имеющему IV группу по электробезопасности",
      "*Работнику, имеющему III группу по электробезопасности и право быть производителем работ",
      "Работнику, имеющему III группу по электробезопасности",
      "Работать единолично не разрешается"], 
      id: 13,
      "type":{"title":""}},
      
      {title:"В каких электроустановках могут выполняться работы в порядке текущей эксплуатации? ",
      variant:["*В электроустановках напряжением до 1000 В",
      "В электроустановках напряжением до и выше 1000 В",
      "В любых электроустановках",
      "Только в электроустановках напряжением не выше 380 В"], 
      id: 14,
      "type":{"title":""}},
      
      {title:"Какие работы из перечисленных можно отнести к работам, выполняемым в порядке текущей эксплуатации в электроустановках напряжением до 1000 В? ",
      variant:["*Снятие и установка электросчетчиков, других приборов и средств измерений",
      "Ремонт пусковой и коммутационной аппаратуры, установленной на щитках",
      "Замена ламп и чистка светильников на высоте более 2,5 м",
      "Любые из перечисленных работ"], 
      id: 15,
      "type":{"title":""}},
      
      {title:"Какие из перечисленных мероприятий необходимо учитывать при оформлении перечня работ, выполняемых в порядке текущей эксплуатации? ",
      variant:["Только условия безопасности и возможности единоличного выполнения конкретных работ",
      "Только квалификацию персонала",
      "Только степень важности электроустановки в целом или ее отдельных элементов в технологическом процессе",
      "*Необходимо учитывать все перечисленные мероприятия"], 
      id: 16,
      "type":{"title":""}},
      
      {title:"Какие запрещающие плакаты вывешиваются на приводах коммутационных аппаратов во избежание подачи напряжения на рабочее место при проведении ремонта или планового осмотра оборудования? ",
      variant:["*Не включать! Работают люди",
      "Не открывать! Работают люди",
      "Работа под напряжением! Повторно не включать!"], 
      id: 17,
      "type":{"title":""}},
       
  ],
  questionsDb:[],
  resultQuestions:localStorage.getItem('resultQuestions') ?
  JSON.parse(localStorage.getItem('resultQuestions')): 
  [
    {title:"В каком случае нарушен порядок хранения и выдачи ключей?",
    variant:[], 
    id: 8,
    result:{
      true_answer:["морковь","редис","тыква","картофель"],
      user_answer:["редис","картофель","тыква","морковь"], 
      score: false
    },
    "type":{"title":"order1"}
    },
    {title:"В каком случае нарушен порядок хранения и выдачи ключей?",
    variant:["морковь","редис","тыква","картофель"], 
    id: 9,
    result:{
      true_answer:[1,2,3,0],
      user_answer:[2,3,1,0], 
      score: false
    },
    "type":{"title":"order"}
    },
    {title:"В каком случае нарушен порядок хранения и выдачи ключей?",
    variant:["морковь","редис","тыква","картофель"], 
    id: 11,
    result:{
      true_answer:[1,2,3,0],
      user_answer:[1,2,3,0], 
      score: true
    },
    "type":{"title":"order"}
    },
    {title:"В каком случае нарушен порядок хранения и выдачи ключей?",
    variant:["На работников промышленных предприятий, в составе которых имеются электроустановки",
    "На работников организаций независимо от форм собственности и организационно-правовых форм и других физических лиц, занятых техническим обслуживанием электроустановок, проводящих в них оперативные переключения, организующих и выполняющих испытания и измерения",
    "*На работников из числа электротехнического, электротехнологического и неэлектротехнического персонала, а также на работодателей",
    "На работников всех организаций независимо от формы собственности, занятых техническим обслуживанием электроустановок и выполняющих в них строительные, монтажные и ремонтные работы"],  
    id: 10,
    result:{
      true_answer:[1,2,3,0],
      user_answer:[2,3,1,0], 
      score: false
    },
    "type":{"title":"order"}
    },
    {title:"В каком случае нарушен порядок хранения и выдачи ключей?",
    variant:[], 
    id: 5,
    result:{true_answer:["морковь"], user_answer:[], score: false},
    "type":{"title":"input_one"}
    },
    {title:"В каком случае нарушен порядок хранения и выдачи ключей?",
    variant:[], 
    id:6,
    result:{true_answer:["морковь"], user_answer:["капуста"], score: false},
    "type":{"title":"input_one"}
    },
    {title:"В каком случае нарушен порядок хранения и выдачи ключей?",
    variant:[], 
    id: 7,
    result:{true_answer:["морковь"], user_answer:["морковь"], score: true},
    "type":{"title":"input_one"}
    },
    {title:"На кого распространяются Правила по охране труда при эксплуатации электроустановок? ",
    variant:["На работников промышленных предприятий, в составе которых имеются электроустановки",
    "На работников организаций независимо от форм собственности и организационно-правовых форм и других физических лиц, занятых техническим обслуживанием электроустановок, проводящих в них оперативные переключения, организующих и выполняющих испытания и измерения",
    "*На работников из числа электротехнического, электротехнологического и неэлектротехнического персонала, а также на работодателей",
    "На работников всех организаций независимо от формы собственности, занятых техническим обслуживанием электроустановок и выполняющих в них строительные, монтажные и ремонтные работы"], 
    id: 1,
    result:{true_answer:[0], user_answer:[], score: false},
    "type":{"title":"radio"},
    },
    
    {title:"При каком условии работники, не обслуживающие электроустановки, могут допускаться в РУ до 1000 В? ",
    variant:["В сопровождении оперативного персонала, обслуживающего данную электроустановку, имеющего группу IV, либо работника, имеющего право единоличного осмотра",
    "*В сопровождении оперативного персонала, обслуживающего данную электроустановку, имеющего группу III, либо работника, имеющего право единоличного осмотра",
    "В сопровождении опытного работника из числа ремонтного персонала, имеющего группу по электробезопасности не ниже V"], 
    id: 2,
    result:{true_answer:[1], user_answer:[1], score: true},
    "type":{"title":"radio"}
    },
    
    {title:"Кто дает разрешение на снятие напряжения при несчастных случаях для освобождения пострадавшего от действия электрического тока?",
    variant:["Разрешение дает оперативный персонал энергообъекта",
    "Разрешение дает вышестоящий оперативный персонал",
    "Разрешение дает административно-технический персонал",
    "*Предварительного разрешения оперативного персонала не требуется. Напряжение должно быть снято немедленно"], 
    id: 3,
    result:{true_answer:[2,3], user_answer:[1,2], score: false},
    "type":{"title":"checkbox"}
    },
    
    {title:"В каком случае нарушен порядок хранения и выдачи ключей?",
    variant:["Ключи от электроустановок должны быть пронумерованы и храниться в запираемом ящике. Один комплект должен быть запасным",
    "Выдача ключей должна быть заверена подписью работника, выдавшего ключ, а также подписью работника, получившего ключ",
    "Ключи от электроустановок , оперативное обслуживание которых осуществляется круглосуточно оперативным персоналом,  должны передаваться по смене с оформлением в оперативном журнале",
    "*Допускается возвращать ключи от электроустановок оперативному персоналу в течение трех дней после полного окончания работ"], 
    id: 4,
    result:{true_answer:[2,3], user_answer:[2,3], score: true},
    "type":{"title":"checkbox"}
    },
    
    // {
    //   id: 4,
    //   title:"В каком случае ключей?",
    //   variant:[
    //     {
    //       "title": "Ключи от электроустановок ",
    //       "answer": 0 , // 0 - неправильный ответ, 1 - правильный -отвеченный, 2 - правильный не отвеченный
    //     },
    //     {
    //       "title": "Выдача ключей должна ",
    //       "answer": 0 , // 0 - неправильный ответ, 1 - правильный -отвеченный, 2 - правильный не отвеченный
    //     },
    //     {
    //       "title": "Ключи от электроустановок",
    //       "answer": 0 , // 0 - неправильный ответ, 1 - правильный -отвеченный, 2 - правильный не отвеченный
    //     },
    //     {
    //       "title": "Допускается возвращать ключи ",
    //       "answer": 0 , // 0 - неправильный ответ, 1 - правильный -отвеченный, 2 - правильный не отвеченный
    //     },
    //   ], 
    
    // result:{ 
    //   user_answer: true,  // true - были ответы от пользователя, false - не  было ответов от пользователя
    //   score: true // true - правильно отвеченный, false - не правильно отвеченный или если не было ответов от пользователя
    // },
    // "type":{"title":"checkbox"} // для checkbox, radio
    // },
    
  ],
  isLoader: false,//resultTicketUser
  resultTicketUser:localStorage.getItem('resultTicketUser') ?
  JSON.parse(localStorage.getItem('resultTicketUser')): [],
  
})






const actions = {
  async getQuestionsDb({dispatch, commit }, {id, slug}) {
    //  const slag = 'mindal-kraiola-ooo-kompaniia-rybvektorzheldorprof' // опен серв
    // const slag = 'korichnyi-ooo-kompaniia-bashkirorion'// докер
    console.log("id - ",  id)
    let url = ''
    if (id === "rnd20" || id === "rnd20t") {
      url = `/api/test/${slug}/question/10`
    } else if (id === "rnd"){
      const i = Math.floor(Math.random() * (30 - 1) )
      console.log("i - ",  i)
      url = `/api/test/${slug}/question/${i}`
    } else {
      url = `/api/ticket/${id}/question`
    }
    try{
      const config = {
        method: 'get',
        url: url,
        headers: { 
          Accept: 'application/json', 
          // Authorization: `Bearer ${token}`
        }
      };
      await axios(config)
        .then(({data})=>{
          console.log("getQuestionsDb - ",  data.questions)
          commit("SET_QUESTIONS", data.questions);
        })
    } catch (e) {
        console.log(e.message);
    }
  },
  
  async getQuestionsTestIdDb({dispatch, commit }, {id, page=null}) {
    console.log("id - ",  id)

    try{
      const config = {
        method: 'get',
        url: `/api/admin/test/${id}/question`,
        headers: { 
          Accept: 'application/json', 
          // Authorization: `Bearer ${token}`
        }
      };
      if (page) {config.url = config.url + `?page=${page}`}
      await axios(config)
        .then(({data})=>{
          console.log("getQuestionsTestIdDb - ",  data.question)
          commit("SET_QUESTIONS", data.question);
          dispatch("setPagination", data.pagination);
        })
    } catch (e) {
        console.log(e.message);
    }
  },
  async setResultDb({dispatch, commit, state }, {token, userAuth} ){
    console.log(JSON.stringify(state.resultTicketUser))
    commit("SET_LOADER_STATUS", true)
    try{
      const config = {
        method: 'post',
        url: '/api/test/handle',
        // url: '/api/auth/test/handle',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json'
          // Authorization: `Bearer ${token}`
        },
        data:  JSON.stringify(state.resultTicketUser)
      };
      if (userAuth) {
        console.log('авторизовался')
        config.url = '/api/auth/test/handle'
        config.headers.Authorization = `Bearer ${token}`
      }
      console.log(config)
     
      await axios(config)
        .then(({data})=>{
          console.log("setResultDb - ",  data)
          commit("SET_RESULT_QUESTIONS", data);
          commit("SET_LOADER_STATUS", false)
        })
        const err = {
          errPrizn: false
        }
       return err
    } catch (e) {
        const err = {
          mes: e.response.data.message,
          errPrizn: true
        }
       return err
    }
  },
  async saveQuestionDb({ dispatch, commit, state }, {token, questionSend} ){
    console.dir(questionSend)
    commit("SET_LOADER_TOGGLE")
    try{
      // Array.from(questionSend).filter(inp => inp.name !== "")
      
      const data = new FormData(questionSend);
      for(let [name, value] of data) {
        console.dir(`${name} = ${value}`); // key1=value1, потом key2=value2

      }
      // questionSend.forEach((element,key) => {
      //   const regexp = new RegExp('img', 'i');
      //   if (regexp.test(element.name) ) {
      //     data.append(`${element.name}`, element.files[0])
      //   } else {
      //     data.append(`${element.name}`, element.value)
      //   }
      // });
      // questionSend.forEach((element,key) => data.append(`${element.name}`, element.value) );
      const config = {
        method: 'post',
        // url: '/api/auth/create/question',
        url: '/api/question/create_with_variant',
        headers: { 
          Accept: 'application/json', 
          // Authorization: `Bearer ${token}`
        },
        data:  data
      };
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log("saveQuestionDb - ",  data)
          dispatch('setMessage', {err: false, mes: data.message})
          commit("SET_LOADER_TOGGLE")
        })
         
      
    } catch (e) {
      console.log(e);
      dispatch('setMessage', {err: true, 
        mes: `${e.response.data.message}  ${e.response.data.error[0]}`
      })
    }
  },
  async importQuestionsFileDb({ dispatch, commit, state }, {id, token, questionSend} ){
    console.dir(questionSend)
    commit("SET_LOADER_TOGGLE")
    try{
      const data = new FormData(questionSend);
      for(let [name, value] of data) {
        console.dir(`${name} = ${value}`); // key1=value1, потом key2=value2

      }
      const config = {
        method: 'post',
        // url: '/api/auth/create/question',
        url: `/api/admin/test/${id}/upload`,
        headers: { 
          Accept: 'application/json', 
          // Authorization: `Bearer ${token}`
        },
        data:  data
      };
      
      console.log(config)
      
      await axios(config)
        .then(({data})=>{
          console.log("saveQuestionDb - ",  data)
          dispatch('setMessage', {err: false, mes: data.message})
          commit("SET_LOADER_TOGGLE")
        })
         
      
    } catch (e) {
      console.log(e);
      dispatch('setMessage', {err: true, 
        mes: `${e.response.data.message}  ${e.response.data.error[0]}`
      })
    }
  },
  setIsLoader({ commit }){
    commit("SET_LOADER_TOGGLE")
  },
  getQuestion(){},
  saveResultTicketUser({ commit }, ticket){
    commit("SET_RESULT_TICKET_USER", ticket);
  }
};

const getters = {
  getQuestions(state) {
    return state.questions 
  },
  getResultQuestions(state) {
    console.log("resultQuestions ", state.resultQuestions)
    return state.resultQuestions 
  },
  getIsLoaderQuestions(state) {
    console.log("isLoader ", state.isLoader)
    return state.isLoader 
  },
  
}

const mutations = {
  [SET_QUESTIONS] (state, questions ) {
    console.log("SET_QUESTIONS", questions)
    state.questions = questions
  },
  [SET_RESULT_QUESTIONS] (state, questions) {
    console.log("SET_RESULT_QUESTIONS", questions)
    state.resultQuestions = questions
    // localStorage.setItem('resultQuestions', JSON.stringify(questions));
  },
  [SET_LOADER_TOGGLE] (state, ) {
    console.log("SET_LOADER_TOGGLE", state.isLoader)
    state.isLoader = !state.isLoader
  },
  [SET_LOADER_STATUS] (state, status) {
    console.log("SET_LOADER_TOGGLE", state.isLoader)
    state.isLoader = status
  },
  [SET_RESULT_TICKET_USER] (state, ticket) {
    console.log("SET_RESULT_TICKET_USER", )
    state.resultTicketUser = ticket
    localStorage.setItem('resultTicketUser', JSON.stringify(ticket));
  }
}

export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}