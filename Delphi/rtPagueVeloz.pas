unit rtPagueVeloz;

interface

uses SysUtils, WinSock, Windows, Dialogs, Classes, SSLSocket, WinSock2, TypInfo,
  Variants, PerlRegEx;

{$M+}

type
  TipoPessoaAPI = (Fisica, Juridica);
  TipoMetodoAPI = (tmPost, tmPut, tmGet, tmDelete);
  TipoTarifaAPI = (BoletoEmissao,BoletoLiquidacao,Saque,NaoTarifar,TransferenciaEfetuada,TransferenciaRecebida,DepositoRecebido);

  //3 primeiros SMS, outros DocumentoFinanceiro (Pendente pros dois)
  //StatusAPI = (NaoDefinido,Pendente,Enviada,Processado,Cancelado,EmProcessamento,EntregueOperadora,FalhaNoEnvio);

  TMeioPagamento = (mpBoleto, mpDeposito);

  TResultAPI = packed record
    Result: Boolean;
    HTML: String;
    HTTPCode: Integer;
    Error: String;
  end;

  TBaseConsulta = class
  //Classe BASE usada para criar as outras classes que precisam de Array
  private
    fId: Integer;
    constructor Create; overload;
    constructor Create(ClassName: String); overload;
  published
    property Id: integer read fId write fId;
  end;
  TBaseArray = Array of TBaseConsulta;

  TItemDto = class(TBaseConsulta)
  private
    fValor: Currency;
    fDocumento: string;
    fPendente: Boolean;
    fTipo: string;
    fData: TDateTime;
  published
    property Data: TDateTime read fData write fData;
    property Tipo: string read fTipo write fTipo;
    property Documento: string read fDocumento write fDocumento;
    property Valor: Currency read fValor write fValor;
    property Pendente: Boolean read fPendente write fPendente;
  end;
  TMovimentacaoExtrato = Array of TItemDto;

  TExtratoAPI = class(TBaseConsulta)
  private
    fSaldoAnterior: Currency;
    fItens: TMovimentacaoExtrato;
  published
    property SaldoAnterior: Currency read fSaldoAnterior write fSaldoAnterior;
    property Itens: TMovimentacaoExtrato read fItens write fItens;
  end;

  TRetornoCompraCreditoSMS = class(TBaseConsulta)
  private
    fValor: Currency;
    fCodigo: string;
    fCreditos: integer;
    fUrlBoleto: string;
    fErro: string;
  published
    property Codigo: string read fCodigo write fCodigo;
    property Creditos: integer read fCreditos write fCreditos;
    property Valor: Currency read fValor write fValor;
    property UrlBoleto: string read fUrlBoleto write fUrlBoleto;
  end;

  TComprarCreditoSMSAPI = class
  private
    fValor: Currency;
    fCreditos: integer;
  published
    property Creditos: integer read fCreditos write fCreditos;
    property Valor: Currency read fValor write fValor;
  end;

  TContaBancariaAPI = class
  private
    fExcluida: Boolean;
    fNumeroConta: integer;
    fCodigoAgencia: integer;
    fDescricao: string;
    fCodigoBanco: integer;
    fId: Integer;
  published
    property CodigoBanco: integer read fCodigoBanco write fCodigoBanco;
    property CodigoAgencia: integer read fCodigoAgencia write fCodigoAgencia;
    property NumeroConta: integer read fNumeroConta write fNumeroConta;
    property Descricao: string read fDescricao write fDescricao;
    property Excluida: Boolean read fExcluida write fExcluida;
    property Id: integer read fId write fId;
  end;

  TContaBancariaNaConsulta = class(TBaseConsulta)
  private
    fExcluida: Boolean;
    fNumeroConta: integer;
    fCodigoAgencia: integer;
    fDescricao: string;
    fCodigoBanco: integer;
    fVersion: integer;
  published
    property CodigoBanco: integer read fCodigoBanco write fCodigoBanco;
    property CodigoAgencia: integer read fCodigoAgencia write fCodigoAgencia;
    property NumeroConta: integer read fNumeroConta write fNumeroConta;
    property Descricao: string read fDescricao write fDescricao;
    property Excluida: Boolean read fExcluida write fExcluida;
    property Version: integer read fVersion write fVersion;
  end;
  TContaBancariaArray = Array of TContaBancariaNaConsulta;
  
  TMensagemSMSNaConsulta = class(TBaseConsulta)
  private
    fEnviadaEm: TDateTime;
    fCadastradaEm: TDateTime;
    //fStatus: StatusAPI;
    fStatus: string;
    fSeuId: string;
  published
    property SeuId: string read fSeuId write fSeuId;
    property CadastradaEm: TDateTime read fCadastradaEm write fCadastradaEm;
    property EnviadaEm: TDateTime read fEnviadaEm write fEnviadaEm;
    //property Status: StatusAPI read fStatus write fStatus;
    property Status: string read fStatus write fStatus;
  end;
  TMensagemSMSArray = Array of TMensagemSMSNaConsulta;

  TDadosMensagemSMSAPI = class
  private
    fSeuId, fTelefoneRemetente, fTelefoneDestino, fConteudo: String;
    fId: Integer;
  published
    property SeuId: String read fSeuId write fSeuId;
    property TelefoneRemetente: String read fTelefoneRemetente write fTelefoneRemetente;
    property TelefoneDestino: String read fTelefoneDestino write fTelefoneDestino;
    property Conteudo: String read fConteudo write fConteudo;
    property Id: Integer read fId write fId;
  end;

  TDadosAssinaturaAPI = class
  private
    fNome, fDocumento, fEmail, fLoginUsuarioDefault: String;
    fTipoPessoa: TipoPessoaAPI;
  published
    property Nome: String read fNome write fNome;
    property Documento: String read fDocumento write fDocumento;
    property Email: String read fEmail write fEmail;
    property LoginUsuarioDefault: String read fLoginUsuarioDefault write fLoginUsuarioDefault;
    property TipoPessoa: TipoPessoaAPI read fTipoPessoa write fTipoPessoa;
  end;

  TPagueVelozAPI = class
  protected
    //JSON
    function JSONConvert(Value: String; PorpType: TTypeKind): Variant;
    function NextChar(Str: String): Char;
    function ValorPadrao(Str: String): Boolean;
    function GetValueByRegEx(JSON, Name: String): String;
    function JSONToArray(var Texto: String; ClassName: String): TBaseArray;
    function JSONFromObject(Objeto: TObject): String;
    procedure JSONToObject(var Objeto: TObject; var Texto: String); overload;
    procedure JSONToObject(var Objeto: TObject; var Texto: String; var BaseA: TBaseArray); overload;

    //Regex IFDEFs
    function RegexSub(SubExpression: Integer): String;
    function RegexMatchLength: Integer;

    //HTTP
    function HttpCode(const Header: string): integer;
    function ErrorCode(const Code: Integer): String;
    function GetIP(HostName: String): String;
    procedure DeleteHttpHeader(var AHtml: string);
  private
    Regex: TPerlRegEx;
    fEndereco, fHost: String;
    fEmail, fToken, fSenha: String;//Concatenar para assinatura (email:token)
    fPorta: Word;
    function ValorArray(Str: String): Boolean;
    function GeraAssinatura: String;
    function ChamaAPI(TipoMetodo: TipoMetodoAPI; Metodo: string; Chave: String = ''; PostData: String = ''): TResultAPI;
  public
    //Assinar
    function Assinar(Dados: TDadosAssinaturaAPI; var Erro: String): Boolean;

    //Extrato
    procedure Extrato(var Retorno: TExtratoAPI; var Erro: String; Inicio: TDateTime = 0; Fim: TDateTime = 0);

    //ContaBancaria
    procedure ListarContaBancaria(var ListaCB: TContaBancariaArray; var Erro: String; Id: Integer = 0);
    function AdicionarContaBancaria(Dados: TContaBancariaAPI; var Erro: String): Boolean;
    function AlterarContaBancaria(Id: Integer; Dados: TContaBancariaAPI; var Erro: String): Boolean;
    function DeletarContaBancaria(Id: Integer; var Erro: String): Boolean;

    //SMS
    function ListarSMS(var Erro: String; Id: Integer = 0): TMensagemSMSArray;
    function SaldoSMS(var prRetorno: String): Integer;
    function EnviarSMS(Dados: TDadosMensagemSMSAPI): string;
    function ComprarCreditoSMS(MeioPagamento: TMeioPagamento; Dados: TComprarCreditoSMSAPI): TRetornoCompraCreditoSMS;

    constructor Create(Producao: Boolean; sEmail, sToken: String);
  published
    property Email: String read fEmail write fEmail;
    property Token: String read fToken write fToken;
    property Senha: String read fSenha write fSenha;
  end;

{$M-}

const
  strMetodo: Array [TipoMetodoAPI] of String =
    ('POST', 'PUT', 'GET', 'DELETE');
  cBase64Codec: array[0..63] of AnsiChar = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
  Base64Filler = '=';


implementation

procedure pGravaLogAPI(prTexto: string);
var
  wNomeArqLog: string;
  wSL: TStringList;
begin
  wSL := TStringList.Create;
  try
    try
      wNomeArqLog := ExtractFilePath(ParamStr(0)) + '_LogAPI.txt';
      if FileExists(wNomeArqLog) then
      begin
        wSL.LoadFromFile(wNomeArqLog);
        if wSL.Count > 9900 then
        begin
          while wSL.Count > 9000 do
            wSL.Delete(wSL.Count -1);
        end;
      end;
      wSL.Insert(0, '');
      wSL.Insert(0, FormatDateTime('yyyy-mm-dd hh:nn:ss:zzz', Now));
      wSL.Insert(0, prTexto);
      wSL.Insert(0, '');
      wSL.SaveToFile(wNomeArqLog);
    except
    end;
  finally
    wSL.Free;
  end;
end;

function CalcEncodedSize(InSize: Cardinal): Cardinal;
begin
  Result:=(InSize div 3) shl 2;
  if ((InSize mod 3) > 0) then
	  Inc(Result, 4);
end;

procedure Base64Encode(const InBuffer; InSize: Cardinal; var OutBuffer); register;
var
  ByThrees, LeftOver: Cardinal;
asm
  mov  ESI, [EAX]
  mov  EDI, [ECX]
  mov  EAX, EBX
  mov  ECX, $03
  xor  EDX, EDX
  div  ECX
  mov  ByThrees, EAX
  mov  LeftOver, EDX
  lea  ECX, cBase64Codec[0]
  xor  EAX, EAX
  xor  EBX, EBX
  xor  EDX, EDX
  cmp  ByThrees, 0
  jz   @@LeftOver
  @@LoopStart:
    LODSW
    mov  BL, AL
    shr  BL, 2
    mov  DL, BYTE PTR [ECX + EBX]
    mov  BH, AH
    and  BH, $0F
    rol  AX, 4
    and  AX, $3F
    mov  DH, BYTE PTR [ECX + EAX]
    mov  AX, DX
    STOSW
    LODSB
    mov  BL, AL
    shr  BX, 6
    mov  DL, BYTE PTR [ECX + EBX]
    and  AL, $3F
    xor  AH, AH
    mov  DH, BYTE PTR [ECX + EAX]
    mov  AX, DX
    STOSW
  dec  ByThrees
  jnz  @@LoopStart
  @@LeftOver:
    cmp  LeftOver, 0
    jz   @@Done
    xor  EAX, EAX
    xor  EBX, EBX
    xor  EDX, EDX
    LODSB
    shl  AX, 6
    mov  BL, AH
    mov  DL, BYTE PTR [ECX + EBX]
    dec  LeftOver
    jz   @@SaveOne
    shl  AX, 2
    and  AH, $03
    LODSB
    shl  AX, 4
    mov  BL, AH
    mov  DH, BYTE PTR [ECX + EBX]
    shl  EDX, 16
    shr  AL, 2
    mov  BL, AL
    mov  DL, BYTE PTR [ECX + EBX]
    mov  DH, Base64Filler
    jmp  @@WriteLast4
  @@SaveOne:
    shr  AL, 2
    mov  BL, AL
    mov  DH, BYTE PTR [ECX + EBX]
    shl  EDX, 16
    mov  DH, Base64Filler
    mov  DL, Base64Filler
  @@WriteLast4:
    mov  EAX, EDX
    ror EAX, 16
    STOSD
  @@Done:
end;

function TPagueVelozAPI.GeraAssinatura: String;
var
	InSize, OutSize: Cardinal;
	PIn, POut: Pointer;
  Auth: String;
begin
  Auth := Email + ':' + Token;
  InSize := Length(Auth);
  OutSize := CalcEncodedSize(InSize);
  SetLength(Result, OutSize);
  PIn := @Auth[1];
  POut := @Result[1];
  Base64Encode(PIn, InSize, POut);
end;

function TPagueVelozAPI.GetIP(HostName: String): String;
var
  wsa: TWSADATA;
  phe: PHostEnt;
  buf: array[0..254] of char;
begin
  Result:= '';
  WSAStartup($202, wsa);
  try
    if (inet_addr(PChar(HostName)) <> INADDR_NONE) then begin
      Result:= HostName;
      exit;
    end;
    if (HostName = '') then begin
      GetHostName(PChar(@buf), SizeOf(buf));
      SetString(HostName, PChar(@buf), StrLen(@buf));
    end;
    phe:= GetHostByName(PChar(HostName));
    if (phe <> nil) then
      Result:= inet_ntoa(PInAddr(phe^.h_addr_list^)^);
  finally
    WSACleanup;
  end;
end;

function TPagueVelozAPI.HttpCode(const Header: string): integer;
var
  code,msg: string;
begin
  msg:= Copy(Header, Pos(' ', Header)+1, MaxInt);
  code:= Copy(msg, 1, Pos(' ', msg)-1);
  Result:= StrToIntDef(code, 0);
end;

function TPagueVelozAPI.DeletarContaBancaria(Id: Integer;
  var Erro: String): Boolean;
var
  Retorno: TResultAPI;
  BaseA: TBaseArray;
  I: Integer;
  wAssin: string;
begin
  wAssin := GeraAssinatura;
  Retorno := ChamaAPI(tmDelete, 'ContaBancaria/' + IntToStr(Id), wAssin);
  Result := Retorno.Result;
  if not Result then
    Erro := Retorno.Error;
end;

procedure TPagueVelozAPI.DeleteHttpHeader(var AHtml: string);
var
  p: integer;
begin
  if (AnsiUpperCase(Copy(AHtml,1,7)) = 'HTTP/1.') then
  begin
    p:= Pos(#13#10#13#10, AHTml);
    if (p > 0) then
    begin
      Delete(AHtml,1,p+3);
      DeleteHttpHeader(AHtml);
    end;
  end;
end;

function TPagueVelozAPI.ChamaAPI;
var
  I: Integer;
  Request, Envia, Authorization: String;
  ss: TStringStream;
  Buff: array[0..4096] of Char;
  https: TSocketSSL;
  Sock: TSocket;
  WSADat: TWSAData;
  SockAddr: sockaddr_in;
  RcvTime: DWord;
  HostIP: string;
  Count: Integer;
  wpDebug: boolean;
begin
  wpDebug := FileExists('debug.');

  Result.Result := False;
  Result.HTML := '';
  try
    if Chave <> '' then
      Authorization := 'Authorization: Basic ' + Chave + #13#10
    else
      Authorization := '';

    Request := Format(
               '%s %s HTTP/1.0'#13#10+
               'Host: %s'#13#10+
               'User-Agent: Mozzilla'#13#10+
               'Content-Type: application/json'#13#10+
               '%s'+//Authorization é Opcional
               'Content-Length: %d'#13#10''#13#10'%s',
               [strMetodo[TipoMetodo], fEndereco + 'V1/'+Metodo, fHost,
                Authorization, Length(PostData), PostData]);

    if wpDebug then pGravaLogAPI(Request);

    HostIP := GetIP(fHost);

    if fPorta = 443 then
    begin
      https := TSocketSSL.Create;

      try
        if https.Connect(fHost, fPorta) then
        begin
          ss := TStringStream.Create('');
          try
            while Length(Request) > 8192 do
            begin
              Envia := Copy(Request, 1, 8192);
              https.Send(Pointer(Envia), Length(Envia)); // max $2003 = 8192 bytes
              Delete(Request, 1, 8192);
            end;

            https.Send(Pointer(Request), Length(Request)); // max $2003 = 8192 bytes

            while True do
            begin
              I := https.Recv(Buff, sizeOf(Buff));
              if (I < 1) then
                Break;
              ss.WriteBuffer(Buff, I);
            end;

            Result.HTML := ss.DataString;

            if wpDebug then pGravaLogAPI(Result.HTML);

            Result.HTTPCode := HttpCode(ss.DataString);
            Result.Error    := ErrorCode(Result.HTTPCode);
            Result.Result   := Result.HTTPCode in [200..254];
            DeleteHttpHeader(Result.HTML);
          finally
            ss.Free;
          end;
        end
        else
        begin
          Result.Result := False;
        end;
      finally
        https.Free;
      end;
    end
    else
    begin
      WSAStartUp(257,WSADat);
      try
        Sock:= socket(AF_INET, SOCK_STREAM, IPPROTO_IP);

        if (Sock = INVALID_SOCKET) then
          exit;

        RcvTime:= 10000;

        if (setsockopt(Sock, SOL_SOCKET, SO_RCVTIMEO, @RcvTime, SizeOf(RcvTime)) <> 0) then
          Exit;

        SockAddr.sin_family := AF_INET;
        SockAddr.sin_port := htons(fPorta);
        SockAddr.sin_addr.S_addr := inet_addr(PChar(HostIP));

        if (connect(Sock, @SockAddr, SizeOf(SockAddr)) <> 0) then
          exit;

        ss := TStringStream.Create('');

        try
          Send(Sock, Pointer(Request)^, Length(Request), 0);

          while True do
          begin
            Count:= recv(Sock, Buff, sizeOf(Buff), 0);
            if (Count < 1) then
              Break;
            ss.WriteBuffer(Buff, Count);
          end;

           Result.HTML := ss.DataString;

           if wpDebug then pGravaLogAPI(Result.HTML);

           Result.HTTPCode := HttpCode(ss.DataString);
           Result.Error := ErrorCode(Result.HTTPCode);
           Result.Result := Result.HTTPCode in [200..254];
           DeleteHttpHeader(Result.HTML);
        finally
          ss.Free;
          shutdown(Sock, SD_BOTH);
          closesocket(Sock);
        end;
      finally
        WSACleanup;
      end;
    end;
  except
    on e: Exception do
    begin
      Result.Result := False;
    end;
  end;
end;

function TPagueVelozAPI.ComprarCreditoSMS(MeioPagamento: TMeioPagamento; Dados: TComprarCreditoSMSAPI): TRetornoCompraCreditoSMS;
var
  Retorno: TResultAPI;
  Metodo, wDados, wAssin: string;
begin
  Result := TRetornoCompraCreditoSMS.Create;
  Result.fValor     := 0;
  Result.fCodigo    := '';
  Result.fCreditos  := 0;
  Result.fUrlBoleto := '';
  Result.fErro      := '';

  if MeioPagamento = mpBoleto then
    Metodo := 'ComprarCreditoSMSPorBoleto'
  else if MeioPagamento = mpDeposito then
    Metodo := 'ComprarCreditoSMSPorDeposito'
  else
  begin
    Result.fErro := 'Método não implementado!';
    Exit;
  end;

  wDados := JSONFromObject(Dados);
  wAssin := GeraAssinatura;

  Retorno := ChamaAPI(tmPost, Metodo, wAssin, wDados);
  if Retorno.Result then
  begin
    if (not Retorno.Result) then
      Result.fErro := Retorno.Error
    else
      JSONToObject(TObject(Result), Retorno.HTML);
  end;
end;

function TPagueVelozAPI.SaldoSMS(var prRetorno: String): Integer;
var
  Retorno: TResultAPI;
  Metodo, wAssin: string;
begin
  Result := 0;
  prRetorno := '';

  Metodo := 'CreditoSMS';
  wAssin := GeraAssinatura;

  Retorno := ChamaAPI(tmGet, Metodo, wAssin);
  if (not Retorno.Result) then
    prRetorno := Retorno.Error
  else
  begin
    Result := StrToIntDef(Retorno.HTML, 0);
    prRetorno := 'OK';
  end;
end;

constructor TPagueVelozAPI.Create(Producao: Boolean; sEmail, sToken: String);
begin
  {$IFDEF VER150}//Para delphi 7 é usada versão específica do TPerlRegEx
  if RegEx = nil then
    RegEx := TPerlRegEx.Create;
  {$ELSE}//Para outros, usar padrão (Ex: Delphi 2006)
  if RegEx = nil then
    RegEx := TPerlRegEx.Create(nil);
  {$ENDIF}

  Email := sEmail;
  Token := sToken;

  if Producao then
  begin //https://api.pagueveloz.com.br/help
    fEndereco := 'https://api.pagueveloz.com.br/';
    fHost := 'api.pagueveloz.com.br';
    fPorta := 443;
  end
  else
  begin //http://pagueveloz.homolog.bludata.net/api/help
    fEndereco := 'http://pagueveloz.homolog.bludata.net/api/';
    fHost := 'pagueveloz.homolog.bludata.net';
    fPorta := 80;
  end;
end;

function TPagueVelozAPI.AdicionarContaBancaria(Dados: TContaBancariaAPI; var Erro: String): Boolean;
var
  Retorno: TResultAPI;
  wDados, wAssin: string;
begin
  wDados := JSONFromObject(Dados);
  wAssin := GeraAssinatura;

  Retorno := ChamaAPI(tmPost, 'ContaBancaria', wAssin, wDados);
  Result := Retorno.Result;
  if not Result then
    Erro := Retorno.Error;
end;

function TPagueVelozAPI.AlterarContaBancaria(Id: Integer; Dados: TContaBancariaAPI; var Erro: String): Boolean;
var
  Retorno: TResultAPI;
  wDados, wAssin: string;
begin
  wDados := JSONFromObject(Dados);
  wAssin := GeraAssinatura;

  Retorno := ChamaAPI(tmPut, 'ContaBancaria/' + IntToStr(Id), wAssin, wDados);
  Erro := '';
  Result := Retorno.Result;
  if not Result then
    Erro := Retorno.Error;
end;

function TPagueVelozAPI.Assinar(Dados: TDadosAssinaturaAPI; var Erro: String): Boolean;
var
  Retorno: TResultAPI;
  wDados: string;
begin
  Erro := '';
  Result := False;

  wDados := JSONFromObject(Dados);

  fEmail  := Dados.Email;
  Retorno := ChamaAPI(tmPost, 'Assinar', '', wDados);
  if Retorno.Result then
  begin
    fToken := GetValueByRegEx(Retorno.HTML, 'Token');
    fSenha := GetValueByRegEx(Retorno.HTML, 'Senha');
    Result := True;
  end
  else
  begin
    Erro := Retorno.Error;
    if Retorno.HTTPCode = 409 then
      Erro := 'E-mail ' + Dados.Email + ' e/ou Documento ' + Dados.fDocumento + ' já cadastradado.';
  end;
end;

procedure TPagueVelozAPI.ListarContaBancaria(var ListaCB: TContaBancariaArray; var Erro: String; Id: Integer);
var
  Metodo: String;
  Retorno: TResultAPI;
  BaseA: TBaseArray;
  I: Integer;
  wAssin: string;
begin
  wAssin := GeraAssinatura;

  Metodo := 'ContaBancaria';
  if Id > 0 then
    Metodo := Metodo + '/' + IntToStr(Id);

  Retorno := ChamaAPI(tmGet, Metodo, wAssin);
  if Retorno.Result then
  begin
    BaseA := JSONToArray(Retorno.HTML, 'TContaBancariaNaConsulta');

    SetLength(ListaCB, Length(BaseA));
    for I := Low(BaseA) to High(BaseA) do
      ListaCB[I] := TContaBancariaNaConsulta(BaseA[I]);
  end
  else
  begin
    SetLength(ListaCB, 0);
    Erro := Retorno.Error;
  end;
end;

// Não tem como criar uma variavel do tipo TMensagemSMSArray para passá-la como parâmetro
{procedure TPagueVelozAPI.ListarSMS(var ListaSMS: TMensagemSMSArray; var Erro: String; Id: Integer = 0);
var
  Metodo: String;
  Retorno: TResultAPI;
  BaseA: TBaseArray;
  I: Integer;
  wAssin: string;
begin
  Metodo := 'MensagemSMS';
  if Id > 0 then
    Metodo := Metodo + '/' + IntToStr(Id);

  wAssin := GeraAssinatura;
  Retorno := ChamaAPI(tmGet, Metodo, wAssin);
  if Retorno.Result then
  begin
    BaseA := JSONToArray(Retorno.HTML, 'TMensagemSMSNaConsulta');

    SetLength(ListaSMS, Length(BaseA));
    for I := Low(BaseA) to High(BaseA) do
      ListaSMS[I] := TMensagemSMSNaConsulta(BaseA[I]);
  end
  else
  begin
    SetLength(ListaSMS, 0);
    Erro := Retorno.Error;
  end;
end;
}

function TPagueVelozAPI.ListarSMS(var Erro: String; Id: Integer = 0): TMensagemSMSArray;
var
  Metodo: String;
  Retorno: TResultAPI;
  BaseA: TBaseArray;
  I: Integer;
  wAssin: string;
begin
  Result := nil;

  Metodo := 'MensagemSMS';
  if Id > 0 then
    Metodo := Metodo + '/' + IntToStr(Id);

  wAssin := GeraAssinatura;
  Retorno := ChamaAPI(tmGet, Metodo, wAssin);
  if Retorno.Result then
  begin
    BaseA := JSONToArray(Retorno.HTML, 'TMensagemSMSNaConsulta');

    SetLength(Result, Length(BaseA));
    for I := Low(BaseA) to High(BaseA) do
      Result[I] := TMensagemSMSNaConsulta(BaseA[I]);
  end
  else
  begin
    Erro := Retorno.Error;
  end;
end;

function TPagueVelozAPI.EnviarSMS(Dados: TDadosMensagemSMSAPI): string;
var
  Retorno: TResultAPI;
  wDados, wAssin: string;
begin
  Result := '';

  wDados := JSONFromObject(Dados);
  wAssin := GeraAssinatura;

  Retorno := ChamaAPI(tmPost, 'MensagemSMS', wAssin, wDados);
  if (Retorno.Result) then
    Result := 'OK'
  else
    Result := Retorno.Error;
end;

function TPagueVelozAPI.ErrorCode(const Code: Integer): String;
begin
  case Code of
    200..299: Result := 'Operação realizada com Sucesso!';
    404: Result := 'Servidor não encontrado';
    409: Result := 'Impossível processar. Item já existente.';
    500: Result := 'Erro de processamento no Servidor!';
  else
    Result := 'Código de Erro: ' + IntToStr(Code);
  end;
end;

procedure TPagueVelozAPI.Extrato(var Retorno: TExtratoAPI; var Erro: String;
  Inicio, Fim: TDateTime);
var
  Metodo: String;
  ResultAPI: TResultAPI;
  Movimentacao: TBaseArray;
  I: Integer;
  wAssin: string;
begin
  Metodo := 'Extrato';
  if (Inicio > 0) or (Fim > 0) then
  begin
    if Inicio = 0 then
      Inicio := Fim - 1;

    if Fim = 0 then
      Fim := Inicio + 1;

    Metodo := Metodo + '?inicio=' + FormatDateTime('yyyy-MM-dd',Inicio) + '&fim=' + FormatDateTime('yyyy-MM-dd', fim);
  end;

  wAssin := GeraAssinatura;
  ResultAPI := ChamaAPI(tmGet, Metodo, wAssin);
  if ResultAPI.Result then
  begin
    Retorno := TExtratoAPI.Create;
    JSONToObject(TObject(Retorno), ResultAPI.HTML, Movimentacao);

    SetLength(Retorno.fItens, Length(Movimentacao));
    for I := Low(Movimentacao) to High(Movimentacao) do
      Retorno.fItens[I] := TItemDto(Movimentacao[I]);
  end
  else
    Erro := ResultAPI.Error;
end;

function TPagueVelozAPI.RegexSub(SubExpression: Integer): String;
begin
{$IFDEF VER150}//Para delphi 7 é usada versão específica do TPerlRegEx
  Result := Regex.Groups[SubExpression];
{$ELSE}//Para outros, usar padrão (Ex: Delphi 2006)
  Result := Regex.SubExpressions[SubExpression];
{$ENDIF}
end;

function TPagueVelozAPI.GetValueByRegEx(JSON, Name: String): String;
begin
  Result := '';
  RegEx.Subject := JSON;
  RegEx.RegEx := '"'+Name+'"\s*:\s*"([^"]+)"';
  if Regex.Match then
    Result := RegexSub(1);
end;

function TPagueVelozAPI.NextChar(Str: String): Char;
begin
  Regex.Subject := Str;
  Regex.RegEx := '^\s*(\S)';
  if Regex.Match then
    Result := RegexSub(1)[1]
  else
    Result := ' ';
end;

function TPagueVelozAPI.ValorPadrao(Str: String): Boolean;
begin
  Regex.Subject := Str;
  Regex.RegEx := '^\s*"([^"]+)"\s*:\s*(".*?"(?<!\\")|-?\d+(?:\.\d*)?|null|true|false)';
  Result := Regex.Match;
end;

function TPagueVelozAPI.ValorArray(Str: String): Boolean;
begin
  Regex.Subject := Str;
  Regex.RegEx := '^\s*"([^"]+)"\s*:\s*\[';
  Result := Regex.Match;
end;

function TPagueVelozAPI.JSONConvert(Value: String; PorpType: TTypeKind): Variant;
var
  Curr: Currency;
begin
  if (Pos('"', Value) = 1) then
  begin
    Delete(Value, 1, 1);
    Delete(Value, Length(Value), 1);
  end;
  Result := Value;

  if (Value = 'null') then
  begin
    case PorpType of
      tkUnknown: Result := Null;
      tkInteger: Result := 0;
      tkChar   : Result := '';
      tkFloat  : Result := 0;
      tkInt64  : Result := 0;
    else
      Result   := Value;
    end; // end case
  end
  else
  begin
    case PorpType of
      tkUnknown    : Result := Null;
      tkInteger    : Result := StrToIntDef(Value, 0);
      tkChar       : Result := Value[1];
      tkFloat      :
        begin
          if not TryStrToCurr(StringReplace(Value,'.',DecimalSeparator,[]), Curr) then
          begin
            if Pos('T', Value) = 11 then
            begin
              Result := EncodeDate(StrToInt(Copy(Value, 1, 4)), StrToInt(Copy(Value, 6, 2)), StrToInt(Copy(Value, 9, 2))) +
                EncodeTime(StrToInt(Copy(Value, 12, 2)), StrToInt(Copy(Value, 15, 2)), StrToInt(Copy(Value, 18, 2)), 0)
            end;
          end
          else
            Result := Curr;
        end;

      tkInt64      : Result := StrToIntDef(Value, 0);
      tkEnumeration: Result := Value;
    else
      Result := Value;
    end; // end case
  end;
end;

function TPagueVelozAPI.RegexMatchLength: Integer;
begin
  {$IFDEF VER150}
    Result := Regex.MatchedLength;
  {$ELSE}
    Result := Regex.MatchedExpressionLength;
  {$ENDIF}
end;

function TPagueVelozAPI.JSONToArray(var Texto: String; ClassName: String): TBaseArray;
var
  ArrayPos: Integer;
begin
  ArrayPos := -1;
  while Texto <> '' do
  begin
    case NextChar(Texto) of
      '[':
      begin
        ArrayPos := -1;
        Delete(Texto, 1, RegexMatchLength);
      end;
      '{':
      begin
        Inc(ArrayPos);
        SetLength(Result, ArrayPos+1);
        Result[ArrayPos] := TBaseConsulta.Create(ClassName);
        Delete(Texto, 1, RegexMatchLength);
        JSONToObject(TObject(Result[ArrayPos]), Texto);
      end;
      ']':
      begin
        Delete(Texto, 1, RegexMatchLength);
        exit;
      end
    else
      Delete(Texto, 1, RegexMatchLength);
    end;
  end;
end;

procedure TPagueVelozAPI.JSONToObject(var Objeto: TObject; var Texto: String; var BaseA: TBaseArray);
var
  PropInfo: PPropInfo;
begin
  while Texto <> '' do
  begin
    case NextChar(Texto) of
      '"':
      begin
        if ValorPadrao(Texto) then
        begin
          PropInfo := GetPropInfo(Objeto, RegexSub(1));
          SetPropValue(Objeto, RegexSub(1), JSONConvert(RegexSub(2), PropInfo^.PropType^.Kind));
          Delete(Texto, 1, RegexMatchLength);
        end
        else
        if ValorArray(Texto) then
        begin
          PropInfo := GetPropInfo(Objeto, RegexSub(1));
          Delete(Texto, 1, RegexMatchLength - 1);
          BaseA := JSONToArray(Texto, PropInfo^.PropType^.Name);
        end;
      end;
      '}':
      begin
        Delete(Texto, 1, RegexMatchLength);
        break;
      end
    else
      Delete(Texto, 1, RegexMatchLength);
    end;
  end;
end;

procedure TPagueVelozAPI.JSONToObject(var Objeto: TObject; var Texto: String); 
var
  BaseA: TBaseArray;
begin
  BaseA := nil;
  JSONToObject(Objeto, Texto, BaseA);
end;

function TPagueVelozAPI.JSONFromObject(Objeto: TObject): String;
var
  Count, Size, I: Integer;
  List: PPropList;
  PropInfo: PPropInfo;
  PropValue: string;
begin
  Result := '{ ';
  Count := GetPropList(Objeto.ClassInfo, tkAny, nil);
  Size  := Count * SizeOf(Pointer);
  GetMem(List, Size);
  try
    Count := GetPropList(Objeto.ClassInfo, tkAny, List);
    for I := 0 to Count - 1 do
    begin
      PropInfo := List^[I];
      if (PropInfo^.PropType^.Kind in tkProperties) and
         (PropInfo^.PropType^.Kind <> tkDynArray) then
      begin
        PropValue := VarToStr(GetPropValue(Objeto, PropInfo^.Name));

        if PropInfo^.PropType^.Kind in [tkInteger, {tkEnumeration,} tkFloat, tkInt64] then
          Result := Result + '"' + PropInfo^.Name + '": ' + PropValue + ','
        else
          Result := Result + '"' + PropInfo^.Name + '": "' + PropValue + '",';
      end;
    end;

    if Length(Result) > 2 then
      Result := Copy(Result, 1, Length(Result)-1) + ' }';

  finally
    FreeMem(List);
    List := nil;
  end;
end;

{ TBaseConsulta }

constructor TBaseConsulta.Create;
begin
  
end;

constructor TBaseConsulta.Create(ClassName: String);
begin
  if ClassName = 'TMensagemSMSNaConsulta' then
    Self := TMensagemSMSNaConsulta.Create()
  else
  if ClassName = 'TContaBancariaNaConsulta' then
    Self := TContaBancariaNaConsulta.Create()
  else
  if (ClassName = 'TMovimentacaoExtrato') or (ClassName = 'TItemDto') then
    Self := TItemDto.Create();
end;

end.

